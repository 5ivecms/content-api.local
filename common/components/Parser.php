<?php

namespace common\components;

use common\components\Proxy\ProxyManager;
use common\models\Proxy;
use common\models\Useragent;
use Curl\Curl;
use Curl\MultiCurl;
use Yii;

class Parser
{
    private $useragentList = [];
    private $multiCurl;
    private $data = null;
    private $urls = [];
    private $dataCurls = [];
    private $failedUrls = [];
    private $proxies = [];
    private $onlyJSONResponse = true;
    private $forceUseProxy;

    public function __construct($forceUseProxy = true)
    {
        $this->forceUseProxy = $forceUseProxy;

        $multiCurl = new MultiCurl();
        $multiCurl->success(function ($instance) {
            $this->curlSuccess($instance);
        });
        $multiCurl->error(function ($instance) {
            $this->curlError($instance);
        });
        $multiCurl->complete(function ($instance) {
            $this->curlComplete($instance);
        });
        $this->multiCurl = $multiCurl;
    }

    public function start()
    {
        $this->loadConfig();

        $i = 0;
        while ($this->hasUrls() && $i < 1) {
            $this->failedUrls = [];
            $this->dataCurls = [];
            foreach ($this->urls as $url) {
                $this->createCurl($url, $this->getRandomProxy());
            }
            foreach ($this->dataCurls as $dataCurl) {
                $this->addCurl($dataCurl['curl']);
            }
            $this->multiCurl->start();
            $this->urls = $this->failedUrls;
            $i++;
        }
    }

    private function loadConfig()
    {
        $proxyManager = new ProxyManager();
        if (Yii::$app->settings->get('proxy', 'enabled') && $this->forceUseProxy) {
            $this->proxies = $proxyManager->getProxy();
            if (!$this->proxies) {
                die('no proxy');
            }
        }
        $this->loadUseragentList();
    }

    private function createCurl($url, $proxy = NULL)
    {
        $dataCurl = [];
        $curl = new Curl();
        $curl->setUrl($url);
        $curl->setUserAgent($this->getRandomUseragent());
        $curl->setOpt(CURLOPT_REFERER, 'https://www.google.ru');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
        $curl->setOpt(CURLOPT_HTTPGET, true);
        $curl->setOpt(CURLOPT_FRESH_CONNECT, 1);
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
        $curl->setOpt(CURLOPT_TIMEOUT, Yii::$app->settings->get('proxy', 'timeout'));

        if (is_array($proxy)) {
            $curl->setProxy($proxy['ip'], $proxy['port'], $proxy['login'], $proxy['password']);
            $curl->setOpt(CURLOPT_PROXYAUTH, CURLAUTH_ANY);
            $curl->setProxyType(Proxy::TYPES[$proxy['type']]);
            if ($proxy['protocol'] == Proxy::PROTOCOL_IPv6) {
                $curl->setOpt(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
            }
            $dataCurl['proxy'] = $proxy['ip'] . ':' . $proxy['port'];
        }

        $dataCurl['curl'] = $curl;
        $dataCurl['url'] = $url;

        $this->dataCurls[] = $dataCurl;
    }

    private function addCurl(Curl $curl)
    {
        $this->multiCurl->addCurl($curl);
    }

    public function addUrls($urls = [])
    {
        if (empty($urls)) {
            return false;
        }

        foreach ($urls as $url) {
            $this->urls[] = $url;
        }

        return true;
    }

    private function getRandomProxy()
    {
        if (empty($this->proxies)) {
            return false;
        }

        return $this->proxies[rand(0, count($this->proxies) - 1)];
    }

    private function loadUseragentList()
    {
        $list = Useragent::find()->asArray()->all();
        foreach ($list as $item) {
            $this->useragentList[] = $item['useragent'];
        }
    }

    private function getRandomUseragent()
    {
        if (empty($this->useragentList)) {
            return '';
        }

        return $this->useragentList[rand(0, count($this->useragentList) - 1)];
    }

    public function setJsonDecoder($mixed)
    {
        $this->multiCurl->setJsonDecoder($mixed);
    }

    public function removeFromFailedUrls($url)
    {
        foreach ($this->failedUrls as $k => $failedUrl) {
            if (trim($failedUrl) == trim($url)) {
                unset($this->failedUrls[$k]);
            }
        }
    }

    public function addFailedUrls($url)
    {
        $isNeeded = true;
        foreach ($this->failedUrls as $k => $failedUrl) {
            if (trim($failedUrl) == trim($url)) {
                $isNeeded = false;
            }
        }
        if ($isNeeded) {
            $this->failedUrls[] = $url;
            return true;
        }

        return false;
    }

    public function hasUrls()
    {
        return count($this->urls) > 0;
    }

    public function hasFailedUrls()
    {
        return count($this->failedUrls) > 0;
    }

    public function getFailedUrls()
    {
        return $this->failedUrls;
    }

    public function getData()
    {
        return $this->data;
    }

    public function clearData()
    {
        $this->data = [];
    }

    public function clearFailedUrls()
    {
        $this->failedUrls = [];
    }

    public function clearUrls()
    {
        $this->urls = [];
    }

    public function setOnlyJSONResponse($condition)
    {
        $this->onlyJSONResponse = $condition;
    }

    public function isJSONResponse($response)
    {
        if (strpos($response, '</BODY>') !== false && strpos($response, '</HTML>') !== false) {
            return false;
        }

        return true;
    }

    private function curlSuccess($instance)
    {
        if ($instance->httpStatusCode === 302) {
            Proxy::setRedirectedStatusProxy($this->findProxyByUrl($instance->url));
            return true;
        } elseif ($this->onlyJSONResponse && !$this->isJSONResponse($instance->response)) {
            $this->addFailedUrls($instance->url);
            if (Yii::$app->settings->get('proxy', 'enabled')) {
                Proxy::updateCaptchaCounterByProxy($this->findProxyByUrl($instance->url));
            }
            return true;
        } else {
            $this->data[] = ['url' => $instance->url, 'response' => $instance->response];
            $this->removeFromFailedUrls($instance->url);
        }
    }

    private function curlError($instance)
    {
        if ($instance->errorCode === 0) {
            if ($this->onlyJSONResponse && $this->isJSONResponse($instance->response)) {
                $this->data[] = ['url' => $instance->url, 'response' => $instance->response];
                $this->removeFromFailedUrls($instance->url);
            }
            return true;
        } else if ($instance->errorCode === 404) {
            $this->data[] = ['url' => $instance->url, 'response' => 404];
            $this->removeFromFailedUrls($instance->url);
            return true;
        } else if ($instance->errorCode != 200) {
            if (Yii::$app->settings->get('proxy', 'enabled')) {
                Proxy::updateErrorsCounterByProxy($this->findProxyByUrl($instance->url));
            }
            $this->addFailedUrls($instance->url);
            return true;
        }
    }

    private function curlComplete($instance)
    {
    }
    
    private function findProxyByUrl($url)
    {
        foreach ($this->dataCurls as $k => $dataCurl) {
            if (trim($this->dataCurls[$k]['url']) == trim($url)) {
                return $this->dataCurls[$k]['proxy'];
            }
        }

        return false;
    }
}
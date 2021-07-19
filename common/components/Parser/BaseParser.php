<?php

namespace common\components\Parser;

use common\components\LinksFilter;
use common\models\{Blacklist, Proxy, Useragent};
use Curl\{Curl, MultiCurl};
use Yii;
use yii\helpers\ArrayHelper;

abstract class BaseParser
{
    protected $urls = [];
    protected $links = [];

    protected $userAgents = [];
    protected $timeout = 10;
    protected $proxyEnabled = false;
    protected $proxy = [];

    protected $config;
    protected $tryCount = 1;
    public $multiCurl;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->loadUseragentList();
        $this->createMultiCurl();
        $this->proxyEnabled = (boolean)Yii::$app->settings->get('enabled', 'proxy');
        if ($this->proxyEnabled) {
            $this->loadProxies();
        }
    }

    protected function createMultiCurl(): void
    {
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

    public function parse(): void
    {
        $k = 0;
        while (count($this->getLinks()) < $this->config->getPagesLimit() * 10 && $k < $this->getTryCount()) {
            $this->addCurlsToMultiCurl($this->getUrls());
            $this->multiCurl->start();
            $k++;
        }
    }

    protected function addCurlsToMultiCurl(array $urls): void
    {
        foreach ($urls as $url) {
            $curl = new Curl();
            $curl->setUrl($url);
            $urlParts = parse_url($url);
            $curl->setUserAgent($this->getRandomUseragentList());
            $curl->setOpt(CURLOPT_REFERER, $urlParts['scheme'] . '//' . $urlParts['host']);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
            $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
            $curl->setOpt(CURLOPT_HTTPGET, true);
            $curl->setOpt(CURLOPT_FRESH_CONNECT, 1);
            $curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
            $curl->setOpt(CURLOPT_TIMEOUT, $this->getTimeout());

            if ($this->proxyEnabled) {
                if (!count($this->proxy)) {
                    die('no-proxy');
                }
                $proxy = $this->getRandomProxy();
                $curl->setOpt(CURLOPT_TIMEOUT, Yii::$app->settings->get('timeout', 'proxy'));
                $curl->setProxy($proxy->ip, $proxy->port, $proxy->login, $proxy->password);
                $curl->setProxyType(Proxy::TYPES[$proxy->type]);
                $curl->setOpt(CURLOPT_PROXYAUTH, CURLAUTH_ANY);
                if ($proxy->protocol == Proxy::PROTOCOL_IPv6) {
                    $curl->setOpt(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
                }
            }
            $this->multiCurl->addCurl($curl);
        }
    }

    public function getUserAgents(): array
    {
        return $this->userAgents;
    }

    public function setUserAgents(array $userAgents): void
    {
        $this->userAgents = $userAgents;
    }

    protected function loadUseragentList(): void
    {
        $this->setUserAgents(ArrayHelper::getColumn(Useragent::find()->asArray()->all(), 'useragent'));
    }

    public function getRandomUseragentList(): string
    {
        $index = rand(0, count($this->getUserAgents()) - 1);
        return $this->getUserAgents()[$index];
    }

    public function setLinks($links): void
    {
        $this->links = $links;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function setUrls(array $urls): void
    {
        $this->urls = $urls;
    }

    public function loadProxies()
    {
        $this->proxy = Proxy::find()->all();
    }

    public function getRandomProxy()
    {
        $index = rand(0, count($this->proxy) - 1);
        return $this->proxy[$index];
    }

    public function linksFilter(array $links): array
    {
        $links = LinksFilter::filterByPath($links);
        $links = LinksFilter::filterByExtension($links);
        $links = LinksFilter::filterByDomain($links, Blacklist::getDomains());

        return array_unique($links);
    }

    public function curlSuccess($instance): void
    {
        echo 'call to "' . $instance->url . '" was successful.' . "\n";
        echo 'response:' . "\n";
    }

    public function curlError($instance): void
    {
        echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
        echo 'error code: ' . $instance->errorCode . "\n";
        echo 'error message: ' . $instance->errorMessage . "\n";
    }

    public function curlComplete($instance): void
    {
        echo 'call completed' . "\n";
    }

    public function setTryCount($count)
    {
        $this->tryCount = $count;
    }

    public function getTryCount()
    {
        return $this->tryCount;
    }

    public function setTimeout($timout)
    {
        $this->timeout = $timout;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }
}
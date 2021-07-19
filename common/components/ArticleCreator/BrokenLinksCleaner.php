<?php

namespace common\components\ArticleCreator;

use Curl\MultiCurl;
use phpQuery;

class BrokenLinksCleaner
{
    private $src;
    private $badSrc = [];

    public function clean($content)
    {
        $this->extractImageSrc($content);
        $this->searchBadSrc();

        $html = phpQuery::newDocument($content);
        foreach ($html->find('img') as $img) {
            $pqImg = pq($img);
            if (in_array($pqImg->attr('src'), $this->getBadSrc())) {
                $pqImg->remove();
            }
        }

        foreach ($html->find('p') as $p) {
            $pqP = pq($p);
            if (empty($pqP->html())) {
                $pqP->remove();
            }
        }

        return $html->html();
    }

    public function extractImageSrc($content)
    {
        $src = [];
        $html = phpQuery::newDocument($content);
        $images = $html->find('img');
        foreach ($images as $image) {
            $pqImage = pq($image);
            array_push($src, $pqImage->attr('src'));
        }

        $this->setSrc($src);
    }

    private function searchBadSrc()
    {
        $multiCurl = new MultiCurl();
        $multiCurl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $multiCurl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
        $multiCurl->setOpt(CURLOPT_HTTPGET, true);
        $multiCurl->setOpt(CURLOPT_FRESH_CONNECT, 1);
        $multiCurl->setOpt(CURLOPT_FOLLOWLOCATION, 1);

        $multiCurl->error(function($instance) {
            $this->addBadSrc($instance->url);
        });
        foreach ($this->getSrc() as $src) {
            $multiCurl->addGet($src);
        }

        $multiCurl->start();
    }

    public function setSrc($src)
    {
        $this->src = $src;
    }

    public function getSrc()
    {
        return $this->src;
    }

    public function setBadSrc($src)
    {
        $this->badSrc = $src;
    }

    public function getBadSrc()
    {
        return $this->badSrc;
    }

    public function addBadSrc($src)
    {
        if (!in_array($src, $this->getBadSrc())) {
            $this->badSrc[] = $src;
        }
    }
}
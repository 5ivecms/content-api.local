<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\{BaseParser, ParserInterface};
use phpQuery;

class LycosParser extends BaseParser implements ParserInterface
{
    const BASE_URL = 'https://search.lycos.com';
    private $nextPageUrl = null;

    public function parse(): void
    {
        $this->proxyEnabled = false;
        for ($i = 0; $i < $this->config->getPagesLimit(); $i++) {
            $this->createUrls();
            parent::parse();
        }
    }

    public function createUrls(): void
    {
        $urls = [];
        $params = [
            'q' => $this->config->getKeyword(),
        ];
        if ($this->getNextPageUrl() !== null) {
            array_push($urls, self::BASE_URL . $this->getNextPageUrl());
        } else {
            array_push($urls, self::BASE_URL . '/web/?' . http_build_query($params));
        }

        $this->setUrls($urls);
    }

    public function extractLinks($html): array
    {
        $links = [];
        $document = phpQuery::newDocument($html);
        foreach ($document->find('.results.search-results')->find('.result-item')->find('.result-title a') as $link) {
            $pqLink = pq($link);
            array_push($links, $pqLink->attr('href'));
        }

        $correctLinks = [];
        foreach ($links as $link) {
            $parts = parse_url($link);
            if (isset($parts['query'])) {
                parse_str($parts['query'],$params);
            }
            if (isset($params['as'])) {
                if (stripos($params['as'], '...') !== false) {
                    continue;
                }
                array_push($correctLinks, $params['as']);
            }
        }

        return $correctLinks;
    }

    public function extractNextPage($html)
    {
        $document = phpQuery::newDocument($html);
        $url = $document->find('.pagination')->find('a[title="Next"]')->attr('href');
        return ($url) ? $url : null;
    }

    public function curlSuccess($instance): void
    {
        if ($instance->httpStatusCode === 200 && isset($instance->response) && !empty($instance->response)) {
            $links = $this->extractLinks($instance->response);
            $links = array_merge($this->getLinks(), $links);
            $links = $this->linksFilter($links);
            $this->setLinks(array_merge($this->getLinks(), $links));
            $this->setNextPageUrl($this->extractNextPage($instance->response));
        }
    }

    public function curlError($instance): void
    {
    }

    public function curlComplete($instance): void
    {
    }

    private function setNextPageUrl($url)
    {
        $this->nextPageUrl = $url;
    }

    private function getNextPageUrl()
    {
        return $this->nextPageUrl;
    }
}
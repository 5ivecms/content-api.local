<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\{BaseParser, ParserInterface};
use phpQuery;

class MyWebSearchParser extends BaseParser implements ParserInterface
{
    const BASE_URL = 'https://search.myway.com/web';

    public function parse(): void
    {
        $this->proxyEnabled = false;
        $this->createUrls();
        //var_dump($this->getUrls());die;
        parent::parse();
    }

    public function createUrls(): void
    {
        $urls = [];
        for ($i = $this->config->getStartPage(); $i <= $this->config->getPagesLimit(); $i++) {
            $params = [
                'q' => $this->config->getKeyword(),
                'ln' => 'ru',
                'page' => $i,
            ];
            array_push($urls, self::BASE_URL . '?' . http_build_query($params));
        }

        $this->setUrls($urls);
    }

    public function extractLinks($html): array
    {
        $links = [];
        $document = phpQuery::newDocument($html);
        foreach ($document->find('.PartialSearchResults-results')->find('.PartialSearchResults-item')->find('.PartialSearchResults-item-title .PartialSearchResults-item-title-link') as $link) {
            $pqLink = pq($link);
            array_push($links, $pqLink->attr('href'));
        }

        return $links;
    }

    public function curlSuccess($instance): void
    {
        if ($instance->httpStatusCode === 200 && isset($instance->response) && !empty($instance->response)) {
            $links = $this->extractLinks($instance->response);
            $links = array_merge($this->getLinks(), $links);
            $links = $this->linksFilter($links);
            $this->setLinks($links);
        }
    }

    public function curlError($instance): void
    {
    }

    public function curlComplete($instance): void
    {
    }
}
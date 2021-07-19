<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\{BaseParser, ParserInterface};
use phpQuery;

class IzitoParser extends BaseParser implements ParserInterface
{
    const HOSTS = [
        'https://www.izito.ws/',
    ];

    public function parse(): void
    {
        $this->proxyEnabled = false;
        $this->createUrls();
        parent::parse();
    }

    public function createUrls():void
    {
        $urls = [];
        for ($i = $this->config->getStartPage(); $i <= $this->config->getPagesLimit(); $i++) {
            $params = [
                'q' => $this->config->getKeyword(),
                'pg' => $i,
            ];
            array_push($urls, $this->getRandomHost() . '?' . http_build_query($params));
        }

        $this->setUrls($urls);
    }

    public function extractLinks($html): array
    {
        $links = [];
        $document = phpQuery::newDocument($html);
        foreach ($document->find('h3')->parent('a') as $link) {
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
        var_dump($instance->errorCode);die;
    }

    public function curlComplete($instance): void
    {
    }

    public function getRandomHost(): string
    {
        $index = rand(0, count(self::HOSTS) - 1);
        return self::HOSTS[$index];
    }
}
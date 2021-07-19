<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\{BaseParser, Configuration, ParserInterface};
use phpQuery;

class DuckDuckParser extends BaseParser implements ParserInterface
{
    const BASE_URL = 'https://api.duckduckgo.com/html';

    public function __construct(Configuration $config)
    {
        parent::__construct($config);
    }

    public function parse(): void
    {
        $this->proxyEnabled = false;
        $this->createUrls();
        parent::parse();
    }

    public function createUrls(): void
    {
        $urls = [];
        $params = [
            'q' => $this->config->getKeyword(),
            'ia' => 'web',
            'kl' => 'ru-ru',
            'df' => 'y'
        ];
        array_push($urls, trim(self::BASE_URL, '/') . '?' . http_build_query($params));

        $this->setUrls($urls);
    }

    public function extractLinks($html): array
    {
        $links = [];
        $document = phpQuery::newDocument($html);
        foreach ($document->find('#links')->find('.results_links')->find('.result__title a') as $link) {
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
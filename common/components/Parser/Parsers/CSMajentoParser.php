<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\BaseParser;
use common\components\Parser\ParserInterface;
use phpQuery;

class CSMajentoParser extends BaseParser implements ParserInterface
{
    const BASE_URL = 'https://cs.majento.ru/search/';

    public function parse(): void
    {
        $this->proxyEnabled = false;
        $this->createUrls();
        parent::parse();
    }

    public function createUrls(): void
    {
        $urls = [];
        for ($i = $this->config->getStartPage(); $i <= $this->config->getPagesLimit(); $i++) {
            $params = [
                'phrase' => $this->config->getKeyword(),
                'pagenum' => $i,
            ];
            array_push($urls, self::BASE_URL . '?' . http_build_query($params));
        }

        $this->setUrls($urls);
    }

    public function extractLinks($html): array
    {
        $links = [];
        $document = phpQuery::newDocument($html);
        foreach ($document->find('#vidacha')->find('a.v_tit') as $link) {
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
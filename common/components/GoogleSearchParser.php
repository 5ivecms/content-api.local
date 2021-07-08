<?php

namespace common\components;

use phpQuery;

class GoogleSearchParser extends Parser
{
    const BASE_URL = 'https://www.google.ru/search';

    private $links = [];

    public function __construct()
    {
        parent::__construct();

        $this->setJsonDecoder(false);
        $this->setOnlyJSONResponse(false);
    }

    public function parseLinks($query, $page = 1)
    {
        $params = [
            'q' => $query,
            'start' => ($page - 1) * 10
        ];

        $queries = http_build_query($params);
        $this->addUrls([self::BASE_URL . '?' . $queries]);
        $this->start();

        $result = $this->getData()[0]['response'];
        if (!$result) {
            return false;
        }

        $links = $this->extractLinks($result);
        $this->setLinks($links);
        $this->clearData();
        $this->clearFailedUrls();

        return true;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function extractLinks($html)
    {
        $links = [];
        $dom = phpQuery::newDocument($html);
        foreach ($dom->find('#search')->find('[data-ved]') as $key => $value) {
            $pq = pq($value);
            $href = $pq->attr('href');
            if ($href !== null && stripos($href, 'http') !== false) {
                array_push($links, $href);
            }
        }

        return $links;
    }

    public function clearLinks()
    {
        $this->links = [];
    }

    public function prepareLinks($links)
    {
        $newLinks = [];
        foreach ($links as $link) {
            if (stripos($link, 'webcache.googleusercontent') === false) {
                array_push($newLinks, $link);
                continue;
            }
            $temp = substr($link, stripos($link, ':http') + 1);
            $parts = explode('+', $temp);
            array_push($newLinks, array_shift($parts));
        }

        return $newLinks;
    }

    public function generateUrls($query, $pages, $start = 1)
    {
        $urls = [];
        for ($i = 1; $i <= $pages; $i++) {
            $params = [
                'q' => $query,
                'start' => ($i - 1) * 10,
                'tbm' => 'nws'
            ];
            array_push($urls, self::BASE_URL . '?' . http_build_query($params));
        }

        return $urls;
    }
}
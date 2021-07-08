<?php

namespace common\components;

use common\models\Blacklist;

class SearxParser extends Parser
{
    const BAD_EXT = ['xml', 'pdf'];
    const INSTANCES = [
        'https://searx.roughs.ru',
        'https://searx.org',
        'https://anon.sx',
        'https://searx.bar',
        'https://searx.be',
        'https://sx.fedi.tech',
        'https://searx.prvcy.eu',
        'https://search.mdosch.de',
        'https://searx.bissisoft.com',
        'https://searx.zackptg5.com',
        'https://searx.tunkki.xyz/searx',
        'https://xeek.com',
        'https://searx.info',
        'https://swag.pw',
        'https://searx.fmac.xyz',
        'https://searx.tux.land',
        'https://searx.rasp.fr',
        'https://searx.webheberg.info',
        'https://s.zhaocloud.net',
        'https://searx.nevrlands.de',
        'https://searx.silkky.cloud',
        'https://metasearch.nl',
        'https://searx.tuxcloud.net',
        'https://searx.sp-codes.de',
        'https://search.bluelock.org',
        'https://search.disroot.org',
        'https://suche.uferwerk.org',
        'https://searx.pwoss.org',
        'https://search.snopyta.org',
        'https://searx.netzspielplatz.de',
        'https://searx.sunless.cloud',
        'https://spot.ecloud.global',
        'https://darmarit.org/searx',
        'https://nibblehole.com',
        'https://www.gruble.de',
        'https://searx.solusar.de',
        'https://searx.devol.it',
        'https://search.azkware.net',
        'https://searx.sk',
        'https://searx.openhoofd.nl',
        'https://searx.gnu.style',
        'https://searx.dresden.network',
        'https://engo.mint.lgbt',
        'https://searx.likkle.monster',
        'https://search.076.ne.jp/searx',
        'https://jsearch.pw',
        'https://searx.monicz.pl',
        'https://searx.xkek.net',
        'https://procurx.pt',
        'https://privatesearch.app',
        'https://searx.nixnet.services',
        'https://searx.tyil.nl',
        'https://trovu.komun.org'
    ];

    private $links = [];

    public function __construct()
    {
        parent::__construct();

        $this->setJsonDecoder(false);
        $this->setOnlyJSONResponse(false);
    }

    public function parse($query, $pages = 4, $startPage = 1)
    {
        $urls = [];
        $links = [];
        $indexInstance = 0;
        $tryCount = 0;

        while (count($links) <= $pages * 10 && $tryCount <= 50) {
            for ($i = $startPage; $i <= $pages; $i++) {
                $params = [
                    'q' => $query,
                    'category_general' => 1,
                    'pageno' => $i,
                    'time_range' => 'year',
                    'language' => 'ru-RU',
                    'format' => 'json',
                    'engines' => 'google',
                    'safesearch' => 0
                ];
                array_push($urls, $this->getRandomHost() . '/search?' . http_build_query($params));
            }

            $this->addUrls($urls);
            $this->start();

            if ($this->getData() !== null) {
                foreach ($this->extractLinks($this->getData()) as $link) {
                    array_push($links, $link);
                }

                $links = LinksFilter::filterByPath($links);
                $links = LinksFilter::filterByExtension($links);
                $links = LinksFilter::filterByDomain($links, Blacklist::getDomains());
                $links = array_unique($links);
            }

            $this->clearData();
            $this->clearFailedUrls();
            $this->clearUrls();

            $urls = [];
            $indexInstance++;
            $tryCount++;
        }

        $this->setLinks(array_unique($links));
    }

    private function extractLinks($data)
    {
        if (!is_array($data)) {
            return false;
        }

        $urls = [];
        foreach ($data as $array) {
            if (!isset($array['response'])) {
                continue;
            }
            $response = json_decode($array['response']);
            if (!isset($response->results) || count($response->results) === 0) {
                continue;
            }
            foreach ($response->results as $item) {
                array_push($urls, $item->url);
            }
        }

        return $urls;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getRandomHost()
    {
        $index = rand(0, count(self::INSTANCES) - 1);
        return self::INSTANCES[$index];
    }
}
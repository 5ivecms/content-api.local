<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\{BaseParser, Configuration, ParserInterface};
use common\models\{Searx};
use yii\helpers\ArrayHelper;

class SearxParser extends BaseParser implements ParserInterface
{
    protected $instances = [];

    public function __construct(Configuration $config)
    {
        parent::__construct($config);
        $this->loadInstances();
        $this->setTryCount(3);
    }

    public function parse(): void
    {
        $k = 0;
        while (count($this->getLinks()) <= $this->config->getPagesLimit() * 10 && $k < $this->getTryCount()) {
            $this->createUrls();
            $this->addCurlsToMultiCurl($this->getUrls());
            $this->multiCurl->start();
            $k++;
        }
    }

    public function createUrls(): void
    {
        $urls = [];
        for ($i = $this->config->getStartPage(); $i <= $this->config->getPagesLimit(); $i++) {
            $params = [
                'q' => $this->config->getKeyword(),
                'category_general' => 1,
                'pageno' => $i,
                'time_range' => 'year',
                'language' => 'ru-RU',
                'format' => 'json',
                'engines' => 'google',
                'safesearch' => 0
            ];
            array_push($urls, trim($this->getRandomInstance(), '/') . '/search?' . http_build_query($params));
        }

        $this->setUrls($urls);
    }

    public function getInstances(): array
    {
        return $this->instances;
    }

    public function setInstances(array $instances): void
    {
        $this->instances = $instances;
    }

    private function loadInstances(): void
    {
        $instances = ArrayHelper::getColumn(Searx::find()->where(['is_blocked' => Searx::IS_NOT_BLOCKED])->asArray()->all(), 'host');
        if (count($instances) === 0) {
            die('no instances');
        }
        $this->setInstances($instances);
    }

    public function getRandomInstance(): string
    {
        $index = rand(0, count($this->getInstances()) - 1);
        return $this->getInstances()[$index];
    }


    public function curlSuccess($instance): void
    {
        if (isset($instance->response->results) && count($instance->response->results) > 0) {
            $links = array_merge($this->getLinks(), ArrayHelper::getColumn($instance->response->results, 'url'));
            $links = $this->linksFilter($links);
            $this->setLinks($links);
        }
    }

    public function curlError($instance): void
    {
        $urlParts = parse_url($instance->url);
        $host = $urlParts['host'];
        Searx::setIsBlockedStatus($host);
    }

    public function curlComplete($instance): void
    {
    }
}
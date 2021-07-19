<?php

namespace common\components\Parser\Parsers;

use common\components\Parser\{BaseParser, Configuration, ParserInterface};
use common\models\Whoogle;
use phpQuery;
use yii\helpers\ArrayHelper;

class WhoogleParser extends BaseParser implements ParserInterface
{
    protected $hosts = [];

    public function __construct(Configuration $config)
    {
        parent::__construct($config);
        $this->loadHosts();
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
        for ($i = $this->config->getStartPage(); $i <= $this->config->getPagesLimit(); $i++) {
            $params = [
                'q' => $this->config->getKeyword(),
                'start' => ($i - 1) * 10,
            ];
            array_push($urls, trim($this->getRandomHost(), '/') . '/search?' . http_build_query($params));
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
        $urlParts = parse_url($instance->url);
        $host = $urlParts['host'];
        Whoogle::setIsBlockedStatus($host);
    }

    public function curlComplete($instance): void
    {
    }

    public function loadHosts(): void
    {
        $hosts = ArrayHelper::getColumn(Whoogle::find()->where(['is_blocked' => Whoogle::IS_NOT_BLOCKED])->asArray()->all(), 'host');
        if (count($hosts) === 0) {
            die('no whoogle hosts');
        }
        $this->setHosts($hosts);
    }

    public function setHosts($hosts): void
    {
        $this->hosts = $hosts;
    }

    public function getHosts(): array
    {
        return $this->hosts;
    }

    public function getRandomHost(): string
    {
        $index = rand(0, count($this->getHosts()) - 1);
        return $this->getHosts()[$index];
    }
}
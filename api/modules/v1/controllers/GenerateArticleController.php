<?php

namespace api\modules\v1\controllers;

use common\components\ContentCreator\BrokenLinksCleaner;
use common\components\ContentCreator\ContentCleaner;
use common\components\ContentCreator\ContentCreator;
use common\components\LinksFilter;
use common\components\SearxParser;
use common\models\Blacklist;

class GenerateArticleController extends BaseController
{
    // http://content-api.local:81/api/v1/generate-articles?keyword=1%D1%85%D0%B1%D0%B5%D1%82%20%D1%81%D0%BA%D0%B0%D1%87%D0%B0%D1%82%D1%8C%20%D0%BD%D0%B0%20%D0%B0%D0%BD%D0%B4%D1%80%D0%BE%D0%B8%D0%B4%20%D0%B1%D0%B5%D1%81%D0%BF%D0%BB%D0%B0%D1%82%D0%BD%D0%BE%202020&mode=1&pagesLimit=1&articlesLimit=10&chunkLimit=10&startPage=1
    public function actionIndex($keyword, $mode = 5, $pagesLimit = 2, $articlesLimit = 10, $chunkLimit = 10, $startPage = 1)
    {
        $parser = new SearxParser();
        $parser->parse($keyword, $pagesLimit, $startPage);
        $links = $parser->getLinks();
        if (!is_array($links)) {
            $result['error'] = 'no links';
            $result['keyword'] = $keyword;

            return $result;
        }

        $links = array_chunk($links, $articlesLimit);
        $links = array_shift($links);

        $contentCreator = new ContentCreator();
        $contentCreator->setMode($mode);
        $contentCreator->setChunksLimit($chunkLimit);
        $contentCreator->setArticlesLimit($articlesLimit);
        $contentCreator->setArticleLinks($links);
        if (!$contentCreator->create()) {
            $result['error'] = 'no create';
            $result['keyword'] = $keyword;

            return $result;
        }

        $contentCreator->generateArticle();
        $articleContent = $contentCreator->getGeneratedArticle();
        if (empty($articleContent)) {
            $result['error'] = 'empty content';
            $result['keyword'] = $keyword;

            return $result;
        }

        $articleContent = ContentCleaner::deleteExternalLinks($articleContent);
        $articleContent = ContentCleaner::fixLazyImages($articleContent);

        $bls = new BrokenLinksCleaner();
        $articleContent = $bls->clean($articleContent);

        $result['content'] = htmlspecialchars_decode($articleContent);
        $result['keyword'] = $keyword;

        return $result;
    }
}
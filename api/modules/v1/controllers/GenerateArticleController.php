<?php

namespace api\modules\v1\controllers;

use common\components\ArticleCreator\{BrokenLinksCleaner, ArticleCreator};
use common\components\Parser\Parsers\{AskParser,
    LycosParser,
    MyWebSearchParser,
    SearxParser,
    EcosiaParser,
    WhoogleParser,
    CSMajentoParser};
use common\components\Parser\{Configuration};

class GenerateArticleController extends BaseController
{
    public function actionIndex($keyword, $mode = 5, $pagesLimit = 2, $articlesLimit = 10, $chunkLimit = 10, $startPage = 1)
    {
        $links = [];
        $parserConfig = new Configuration($keyword, $pagesLimit, $startPage);
        $parsers = [
            new LycosParser($parserConfig),
            new EcosiaParser($parserConfig),
            new MyWebSearchParser($parserConfig),
            new AskParser($parserConfig),
            new WhoogleParser($parserConfig),
            new SearxParser($parserConfig),
            new CSMajentoParser($parserConfig),
        ];
        shuffle($parsers);
        foreach ($parsers as $parser) {
            if (count($links) > $articlesLimit * 1.5) {
                break;
            }
            $parser->parse();
            $links = array_unique(array_merge($links, $parser->getLinks()));
        }

        if (!count($links)) {
            $result['error'] = 'no links';
            $result['keyword'] = $keyword;

            return $result;
        }

        $links = array_chunk($links, $articlesLimit * 2);
        $links = array_shift($links);

        $articleCreator = new ArticleCreator();
        $articleCreator->setMode($mode);
        $articleCreator->setChunksLimit($chunkLimit);
        $articleCreator->setArticlesLimit($articlesLimit);
        $articleCreator->setArticleLinks($links);
        if (!$articleCreator->create()) {
            $result['error'] = 'no create';
            $result['keyword'] = $keyword;

            return $result;
        }

        $articleCreator->generateArticle();
        $articleContent = $articleCreator->getGeneratedArticle();
        if (empty($articleContent)) {
            $result['error'] = 'empty content';
            $result['keyword'] = $keyword;

            return $result;
        }

        $bls = new BrokenLinksCleaner();
        $articleContent = $bls->clean($articleContent);

        $result['content'] = htmlspecialchars_decode($articleContent);
        $result['keyword'] = $keyword;

        return $result;
    }
}
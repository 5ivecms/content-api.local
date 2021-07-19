<?php

namespace frontend\controllers;

use common\components\ArticleCreator\{BrokenLinksCleaner, ArticleCreator};
use common\components\Parser\Parsers\{AskParser,
    LycosParser,
    MyWebSearchParser,
    SearxParser,
    EcosiaParser,
    WhoogleParser,
    CSMajentoParser};
use common\components\Parser\{Configuration};
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        $this->parse('Vetus maps старинные карты для андроид бесплатно', 5, 2, 10, 1, 1);
        //$this->parse('1win скачать на андроид бесплатно 1winbk official', 5, 3, 30, 1, 1);
        die;
        return $this->render('index');
    }

    public function parse($keyword, $mode = 1, $pagesLimit = 2, $articlesLimit = 10, $chunkLimit = 10, $startPage = 1)
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
            if (count($links) > $articlesLimit) {
                break;
            }
            $parser->parse();
            $links = array_unique(array_merge($links, $parser->getLinks()));
        }

        if (!count($links)) {
            return 'error';
        }

        $links = array_chunk($links, $articlesLimit * 2);
        $links = array_shift($links);

        $articleCreator = new ArticleCreator();
        $articleCreator->setMode($mode);
        $articleCreator->setChunksLimit($chunkLimit);
        $articleCreator->setArticlesLimit($articlesLimit);
        $articleCreator->setArticleLinks($links);
        if (!$articleCreator->create()) {
            return 'error';
        }

        $articleCreator->generateArticle();
        $articleContent = $articleCreator->getGeneratedArticle();
        if (empty($articleContent)) {
            $result['content'] = $articleContent;
            $result['keyword'] = $keyword;
            echo $result['content'];
            die;
        }

        $bls = new BrokenLinksCleaner();
        $articleContent = $bls->clean($articleContent);

        $result['content'] = $articleContent;
        $result['keyword'] = $keyword;

        echo $result['content'];
        die;
    }
}

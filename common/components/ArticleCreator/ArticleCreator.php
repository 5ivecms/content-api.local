<?php

namespace common\components\ArticleCreator;

use andreskrey\Readability\Configuration;
use andreskrey\Readability\Readability;
use common\components\Parser;

class ArticleCreator
{
    const CHUNK_TAG = 'h2';

    private $articleLinks;
    private $articles = [];
    private $mode = 1;
    private $chunksLimit = 5;
    private $articlesLimit = 5;
    private $generatedArticle;
    private $chunks = [];

    public function create()
    {
        if (!$this->createArticles()) {
            return false;
        }
        $this->filterArticlesByTags(['template', 'figure']);
        if ($this->getMode() !== 1) {
            $this->articleChunk();
            $this->deleteArticlesWithEmptyChunks();
            $this->sortArticleByCountChunk();
        }

        return true;
    }

    public function setArticleLinks($links)
    {
        $this->articleLinks = $links;
    }

    public function getArticleLinks()
    {
        return $this->articleLinks;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    private function createArticles()
    {
        $parser = new Parser(false);

        $parser->setJsonDecoder(false);
        $parser->setOnlyJSONResponse(false);
        $parser->addUrls($this->getArticleLinks());
        $parser->start();
        $result = $parser->getData();
        $parser->clearData();
        $parser->clearFailedUrls();
        if (!$result) {
            return false;
        }
        
        foreach ($result as $k => $item) {
            if (isset($item['response']) && stripos($item['response'], '1251') !== false) {
                unset($result[$k]);
                continue;
            }
            if (isset($item['response']) && stripos($item['response'], '<title>') === false) {
                unset($result[$k]);
                continue;
            }
            if (strlen($item['response']) > 800000) {
                unset($result[$k]);
                continue;
            }
        }

        foreach ($result as $data) {
            if (!isset($data['response']) || !isset($data['url']) || empty($data['response']) || empty($data['url'])) {
                continue;
            }

            $readability = new Readability(new Configuration([
                'SummonCthulhu' => true,
                'OriginalURL' => $data['url'],
                'FixRelativeURLs' => true,
                'NormalizeEntities' => false,
                'SubstituteEntities' => false
            ]));

            try {
                $readability->parse($data['response']);
                $this->addArticle(
                    $readability->getTitle(),
                    $readability->getContent(),
                    $data['url']
                );
            } catch (\Exception $e) {
                continue;
            }

            unset($readability);
        }

        return true;
    }

    private function addArticle($title, $content, $url)
    {
        $article = new Article($title, $content, $url);
        array_push($this->articles, $article);
    }

    private function filterArticlesByTags($tags)
    {
        foreach ($this->articles as $k => $article) {
            $articleHtml = \phpQuery::newDocument($article->getContent());
            foreach ($articleHtml->find('*') as $element) {
                if (in_array($element->tagName, $tags)) {
                    unset($this->articles[$k]);
                    continue 2;
                }
            }
        }

        $this->articles = array_values($this->articles);
    }

    private function articleChunk()
    {
        foreach ($this->getArticles() as $article) {
            $chunks = [];
            $index = 0;
            $articleHtml = \phpQuery::newDocument($article->getContent());
            $h2Headers = $articleHtml->find(self::CHUNK_TAG);

            foreach ($h2Headers as $h2) {
                $pqH2 = pq($h2);
                $chunks[$index][] = $pqH2->htmlOuter();
                foreach ($pqH2->nextAll()->elements as $element) {
                    $pqElement = pq($element);
                    if ($element->tagName !== self::CHUNK_TAG) {
                        $chunks[$index][] = $pqElement->htmlOuter();
                    } else {
                        $article->addChunk(implode($chunks[$index], ''));
                        $index++;
                        continue 2;
                    }
                }
            }
        }
    }

    private function deleteArticlesWithEmptyChunks()
    {
        foreach ($this->articles as $k => $article) {
            if (count($article->getChunks()) === 0) {
                unset($this->articles[$k]);
            }
        }
        $this->articles = array_values($this->articles);
    }

    private function sortArticleByCountChunk()
    {
        $articles = $this->getArticles();
        usort($articles, function($obj1, $obj2) {
            return (-1) * ($obj1->getChunksCount() - $obj2->getChunksCount());
        });
        $this->articles = array_values($articles);
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setChunksLimit($count)
    {
        $this->chunksLimit = $count;
    }

    public function getChunksLimit()
    {
        return $this->chunksLimit;
    }

    public function setArticlesLimit($count)
    {
        $this->articlesLimit = $count;
    }

    public function getArticlesLimit()
    {
        return $this->articlesLimit;
    }

    public function getGeneratedArticle()
    {
        return $this->generatedArticle;
    }

    public function setGeneratedArticle($article)
    {
        $this->generatedArticle = $article;
    }

    public function addChunk($chunk)
    {
        array_push($this->chunks, $chunk);
    }

    public function generateArticle()
    {
        switch ($this->mode) {
            case 1:
                $this->generateModeOne();
                break;
            case 2:
                $this->generateModeTwo();
                break;
            case 3:
                $this->generateModeThree();
                break;
            case 4:
                $this->generateModeFour();
                break;
            case 5:
                $this->generateModeFive();
                break;
        }

        $chunksContent = [];
        foreach ($this->chunks as $k => $chunk) {
            $preparedChunk = $this->prepareChunk($chunk);
            array_push($chunksContent, $preparedChunk['content']);
        }

        $this->generatedArticle = implode(PHP_EOL, $chunksContent);
    }

    private function prepareChunk($chunk)
    {
        $newChunkContent = ArticleCleaner::deleteExternalLinks($chunk['content']);
        $newChunkContent = ArticleCleaner::fixLazyImages($newChunkContent, $chunk['url']);
        return ['url' => $chunk['url'], 'content' => $newChunkContent];
    }

    private function randomFirstChunk()
    {
        $articleIndex = rand(0, count($this->articles) - 1);
        $randArticle = $this->articles[$articleIndex];
        $randArticleChunks = $randArticle->getChunks();
        $firstChunk = array_shift($randArticleChunks);
        $randArticle->setChunks($randArticleChunks);
        $this->articles[$articleIndex] = $randArticle;

        return $firstChunk;
    }

    private function generateModeOne()
    {
        $parts = [];
        $articles = array_chunk($this->articles, $this->getArticlesLimit());
        $articles = array_shift($articles);
        foreach ($articles as $article) {
            array_push($parts, $article->getContent());
        }

        $this->generatedArticle = implode(PHP_EOL, $parts);
    }

    private function generateModeTwo()
    {
        $parts = [];
        $firstChunk = $this->randomFirstChunk();
        $articles = array_chunk($this->articles, $this->getArticlesLimit());
        $articles = array_shift($articles);
        foreach ($articles as $article) {
            $parts = array_merge($parts, $article->getChunks());
        }
        shuffle($parts);
        array_unshift($parts, $firstChunk);
        $this->generatedArticle = implode(PHP_EOL, $parts);
    }

    private function generateModeThree()
    {
        $parts = [];
        $firstChunk = $this->randomFirstChunk();
        $articles = array_chunk($this->articles, $this->getArticlesLimit());
        $articles = array_shift($articles);
        foreach ($articles as $article) {
            $parts = array_merge($parts, $article->getChunks());
        }
        shuffle($parts);
        $chunks = [];
        if ($this->getChunksLimit() > 1) {
            $chunks = array_chunk($parts, $this->getChunksLimit() - 1);
            $chunks = array_shift($chunks);
        }
        array_unshift($chunks, $firstChunk);

        $this->generatedArticle = implode(PHP_EOL, $chunks);
    }

    private function generateModeFour()
    {
        $parts = [];
        $firstChunk = $this->randomFirstChunk();
        $articles = array_chunk($this->articles, $this->getArticlesLimit());
        $articles = array_shift($articles);
        foreach ($articles as $article) {
            $articleChunks = $article->getChunks();
            if (count($articleChunks) === 0) {
                continue;
            }
            $lastIndexChunks = count($articleChunks) - 1;
            $randomChunk = $articleChunks[rand(0, $lastIndexChunks)];
            array_push($parts, $randomChunk);
        }
        array_unshift($parts, $firstChunk);
        $this->generatedArticle = implode(PHP_EOL, $parts);
    }

    private function generateModeFive()
    {
        if (count($this->getArticles()) === 0) {
            $this->generatedArticle = '';
            return false;
        }

        $articles = array_chunk($this->getArticles(), $this->getArticlesLimit());
        $articles = array_shift($articles);

        foreach ($articles as $article) {
            $articleChunks = $article->getChunks();
            if (count($articleChunks) === 0) {
                continue;
            }
            foreach ($articleChunks as $articleChunk) {
                $this->addChunk(['url' => $article->getUrl(), 'content' => $articleChunk]);
            }
        }

        return true;
    }
}
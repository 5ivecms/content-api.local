<?php

namespace common\components\ArticleCreator;

class Article
{
    private $title;
    private $content;
    private $url;
    private $chunksCount = 0;
    private $chunks = [];

    public function __construct($title, $content, $url)
    {
        $this->title = $title;
        $this->content = $content;
        $this->url = $url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function addChunk($chunk)
    {
        array_push($this->chunks, $chunk);
        $this->incrementChunksCount();
    }

    public function getChunks()
    {
        return $this->chunks;
    }

    public function setChunks($chunks)
    {
        $this->chunks = $chunks;
    }

    public function incrementChunksCount()
    {
        $this->chunksCount += 1;
    }

    public function getChunksCount()
    {
        return $this->chunksCount;
    }
}
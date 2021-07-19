<?php

namespace common\components\Parser;

class Configuration
{
    private $keyword;
    private $pagesLimit;
    private $startPage;

    public function __construct($keyword, $pagesLimit = 2, $startPage = 1)
    {
        $this->setKeyword($keyword);
        $this->setPagesLimit($pagesLimit);
        $this->setStartPage($startPage);
    }

    public function getKeyword(): string
    {
        return $this->keyword;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    public function getPagesLimit(): string
    {
        return $this->pagesLimit;
    }

    public function setPagesLimit($pagesLimit)
    {
        $this->pagesLimit = $pagesLimit;
    }

    public function getStartPage(): string
    {
        return $this->startPage;
    }

    public function setStartPage($startPage)
    {
        $this->startPage = $startPage;
    }
}
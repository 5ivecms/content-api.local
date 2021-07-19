<?php

namespace common\components\ArticleCreator;

use phpQuery;

class ArticleCleaner
{
    public static function deleteExternalLinks($article)
    {
        return preg_replace('#<a.*?>|</a>#sui', '', $article);
    }

    public static function fixLazyImages($article, $articleUrl)
    {
        $urlParts = parse_url($articleUrl);
        $baseUrl = $urlParts['scheme'] . '://' . $urlParts['host'];

        $articleHtml = phpQuery::newDocument($article);
        foreach ($articleHtml->find('img') as $img) {
            $pqImg = pq($img);
            $pqImg->attr('src', str_replace('\/', '/', $pqImg->attr('src')));
            $pqImg->attr('src', str_replace('%5C', '', $pqImg->attr('src')));

            if ($pqImg->attr('data-lazy-src')) {
                $pqImg->attr('src', self::addHostToUrl($pqImg->attr('data-lazy-src'), $baseUrl));
                continue;
            }
            if ($pqImg->attr('data-wpfc-original-src')) {
                $pqImg->attr('src', self::addHostToUrl($pqImg->attr('data-wpfc-original-src'), $baseUrl));
                continue;
            }
            if (stripos($pqImg->attr('src'), 'data:image') !== false && $pqImg->attr('data-src')) {
                $pqImg->attr('src', self::addHostToUrl($pqImg->attr('data-src'), $baseUrl));
                continue;
            }
            if (stripos($pqImg->attr('src'), 'data:image') !== false && $pqImg->attr('data-mysrc')) {
                $pqImg->attr('src', self::addHostToUrl($pqImg->attr('data-mysrc'), $baseUrl));
                continue;
            }
            if ($pqImg->attr('data-lazy-type') && $pqImg->attr('data-src') && $pqImg->attr('data-lazy-type') === 'image') {
                $pqImg->attr('src', self::addHostToUrl($pqImg->attr('data-lazy-type'), $baseUrl));
                continue;
            }
        }

        return $articleHtml->html();
    }

    private static function addHostToUrl($url, $baseUrl)
    {
        if (stripos($url, $baseUrl) === false && stripos($url, 'http') === false) {
            return $baseUrl . '/' . trim($url, '/');
        }

        return $url;
    }
}
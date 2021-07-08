<?php

namespace common\components\ContentCreator;

use phpQuery;

class ContentCleaner
{
    public static function deleteExternalLinks($article)
    {
        return preg_replace('#<a.*?>|</a>#sui', '', $article);
    }

    public static function deleteNbsp($article)
    {
        return str_replace('&nbsp;', ' ', $article);
    }

    public static function fixLazyImages($article)
    {
        $articleHtml = phpQuery::newDocument($article);
        foreach ($articleHtml->find('img') as $img) {
            $pqImg = pq($img);
            $pqImg->attr('src', str_replace('\/', '/', $pqImg->attr('src')));
            $pqImg->attr('src', str_replace('%5C', '', $pqImg->attr('src')));
            if ($pqImg->attr('data-lazy-src')) {
                $pqImg->attr('src', $pqImg->attr('data-lazy-src'));
                continue;
            }
            if ($pqImg->attr('data-wpfc-original-src')) {
                $pqImg->attr('src', $pqImg->attr('data-wpfc-original-src'));
                continue;
            }
            if (stripos($pqImg->attr('src'), 'data:image') !== false && $pqImg->attr('data-src')) {
                $pqImg->attr('src', $pqImg->attr('data-src'));
                continue;
            }
            if (stripos($pqImg->attr('src'), 'data:image') !== false && $pqImg->attr('data-mysrc')) {
                $pqImg->attr('src', $pqImg->attr('data-mysrc'));
                continue;
            }
        }

        return $articleHtml->html();
    }
}
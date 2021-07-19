<?php

namespace common\components;

class LinksFilter
{
    const BAD_EXT = ['xml', 'pdf'];

    public static function filterByExtension($links)
    {
        return array_filter($links, function ($link) {
            return !in_array(self::getExtension($link), self::BAD_EXT);
        });
    }

    public static function filterByDomain($links, $domains)
    {
        return array_filter($links, function ($link) use($domains) {
            $urlParts = parse_url($link);
            $host = str_replace('www.', '', $urlParts['host']);
            return !in_array($host, $domains);
        });
    }

    private static function getExtension($url)
    {
        return substr($url, strrpos($url, '.') + 1);
    }

    public static function filterByPath($links)
    {
        return array_filter($links, function ($link) {
            $urlParts = parse_url($link);
            return isset($urlParts['path']) && $urlParts['path'] !== '' && $urlParts['path'] !== '/';
        });
    }
}
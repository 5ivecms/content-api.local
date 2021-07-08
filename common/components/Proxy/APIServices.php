<?php

namespace common\components\Proxy;

class APIServices
{
    const API_KEY_MACROS = '{api_key}';

    const PROXY6_NET = 'proxy6.net';
    const BEST_PROXIES_RU = 'best-proxies.ru';

    const SERVICES = [
        self::PROXY6_NET,
        self::BEST_PROXIES_RU
    ];

    const URLS = [
        self::PROXY6_NET => 'https://proxy6.net/api/' . self::API_KEY_MACROS . '/getproxy',
        self::BEST_PROXIES_RU => 'https://api.best-proxies.ru/proxylist.json?key=' . self::API_KEY_MACROS . '&level=1,2&speed=1&uptime=1&country=ru&limit=0',
    ];
}
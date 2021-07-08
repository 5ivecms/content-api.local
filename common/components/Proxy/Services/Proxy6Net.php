<?php

namespace common\components\Proxy\Services;

use common\components\Proxy\APIServices;
use common\models\Proxy;
use Yii;

class Proxy6Net
{
    public static function getProxy($token)
    {
        if (empty($token)) {
            return false;
        }

        $proxies = Yii::$app->cache->get('proxy.' . APIServices::PROXY6_NET . '.list');
        if ($proxies) {
            return $proxies;
        }

        $url = str_replace(APIServices::API_KEY_MACROS, $token, APIServices::URLS[APIServices::PROXY6_NET]);

        try {
            $result = file_get_contents($url);
        } catch (\Exception $e) {
            return false;
        }

        $result = json_decode($result);
        if (!$result || !isset($result->list_count) || $result->list_count == 0 || !isset($result->list)) {
            return false;
        }

        $proxies = [];
        foreach ($result->list as $item) {
            $proxy = [];
            $proxy['ip'] = $item->host;
            $proxy['port'] = $item->port;
            $proxy['type'] = ($item->type === 'socks') ? Proxy::PROXY_SOCKS5 : Proxy::PROXY_HTTPS;
            $proxy['protocol'] = ($item->version === '6') ? Proxy::PROTOCOL_IPv6 : Proxy::PROTOCOL_IPv4;
            $proxy['login'] = $item->user;
            $proxy['password'] = $item->pass;
            $proxies[] = $proxy;
        }

        Yii::$app->cache->set('proxy.' . APIServices::PROXY6_NET . '.list', $proxies, 60*10);

        return $proxies;
    }
}
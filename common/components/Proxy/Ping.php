<?php

namespace common\components\Proxy;

class Ping
{
    public static function get($url, $ip, $port, $type, $protocol, $login = '', $password = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_PROXY, $ip . ':' . $port);
        curl_setopt($ch, CURLOPT_PROXYTYPE, $type);
        if (!empty($login) && !empty($password)) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $login . ':' . $password);
        }
        if ($protocol == \common\models\Proxy::PROTOCOL_IPv6) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        }
        curl_setopt($ch, CURLOPT_PROXYTYPE, \common\models\Proxy::TYPES[$type]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return $info;
    }
}
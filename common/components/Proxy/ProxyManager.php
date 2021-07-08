<?php

namespace common\components\Proxy;

use common\models\Proxy;
use common\models\Setting;

class ProxyManager
{
    private $settings;
    private $proxy;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->loadSettings();
        $this->loadProxy();
    }

    public function getProxy()
    {
        if (!$this->proxy) {
            return [];
        }

        return $this->proxy;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    private function loadSettings()
    {
        $this->settings = Setting::getGroupSettings('proxy', false);
    }

    private function loadProxy()
    {
        $this->proxy = Proxy::getActiveProxyAsArray();
    }
}
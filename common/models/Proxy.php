<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "proxy".
 *
 * @property int $id
 * @property string $ip
 * @property string $port
 * @property string $type
 * @property string $protocol
 * @property string $login
 * @property string $password
 * @property int $totalTime
 * @property int $connectTime
 * @property int $pretransferTime
 * @property int $countCaptcha
 * @property int $countErrors
 * @property int $redirected
 * @property int $status
 */
class Proxy extends \yii\db\ActiveRecord
{
    public $list;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 1;

    const PROXY_SOCKS4 = 'socks4';
    const PROXY_SOCKS5 = 'socks5';
    const PROXY_HTTPS = 'https';
    const TYPES = [
        self::PROXY_SOCKS4 => CURLPROXY_SOCKS4,
        self::PROXY_SOCKS5 => 7,
        self::PROXY_HTTPS => 2,
    ];

    const PROTOCOL_IPv6 = 'ipv6';
    const PROTOCOL_IPv4 = 'ipv4';
    const PROTOCOLS = [self::PROTOCOL_IPv4, self::PROTOCOL_IPv6];

    const DEFAULT_SATS = [
        'totalTime' => 0,
        'connectTime' => 0,
        'pretransferTime' => 0,
        'countCaptcha' => 0,
        'countErrors' => 0,
        'redirected' => 0,
        'status' => 1,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proxy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'port', 'type'], 'required'],
            [['totalTime', 'connectTime', 'pretransferTime', 'countCaptcha', 'redirected', 'countErrors', 'status'], 'integer'],
            [['protocol'], 'string', 'max' => 12],
            [['ip', 'port', 'type', 'login', 'password'], 'string', 'max' => 255],
            [['list'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'IP',
            'port' => 'Порт',
            'type' => 'Тип',
            'protocol' => 'Протокол',
            'list' => 'Список прокси',
            'login' => 'Логин',
            'password' => 'Пароль',
            'ping' => 'Пинг',
            'countCaptcha' => 'Капчи',
            'countErrors' => 'Ошибок',
            'redirected' => 'Редирект'
        ];
    }

    public static function setRedirectedStatusProxy($proxy)
    {
        $model = self::selectProxy($proxy);
        $model->redirected = 1;
        $model->status = 0;

        return $model->save();
    }

    public static function updateErrorsCounterByProxy($proxy)
    {
        $model = self::selectProxy($proxy);
        $model->updateCounters(['countErrors' => 1]);
    }

    public static function updateCaptchaCounterByProxy($proxy)
    {
        $model = self::selectProxy($proxy);
        $model->updateCounters(['countCaptcha' => 1]);
    }

    public static function selectProxy($proxy) {
        if (!$proxy) {
            return false;
        }
        $parts = explode(':', $proxy);
        $ip = $parts[0];
        $port = $parts[1];
        $model = Proxy::find()->where(['ip' => $ip])->andWhere(['port' => $port])->one();
        if (!$model) {
            return false;
        }

        return $model;
    }

    public static function getActiveProxyAsArray()
    {
        return Proxy::find()->where(['status' => 1])->andWhere(['<=', 'connectTime',  Setting::getProxySettings()['ping']])->asArray()->all();
    }
}

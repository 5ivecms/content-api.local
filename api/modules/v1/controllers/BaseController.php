<?php

namespace api\modules\v1\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

    public function checkAccess($action, $model = null, $params = [])
    {
        return true;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formatParam' => 'format',
            'formats' => [
                'application\json' => Response::FORMAT_JSON,
                'application\xml' => Response::FORMAT_XML,
            ]
        ];
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete', 'view', 'options', 'index'];
        //$behaviors['authenticator']['only'] = ['create', 'update', 'delete', 'view', 'options',];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            //'only' => ['create', 'update', 'delete', 'view', 'options'],
            'only' => ['create', 'update', 'delete', 'view', 'options', 'index'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }
}
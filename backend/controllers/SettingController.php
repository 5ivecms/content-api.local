<?php

namespace backend\controllers;

use common\models\Setting;
use Yii;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

class SettingController extends BaseController
{
    public function actionUpdate()
    {
        $post = Yii::$app->request->post();
        if ($post) {
            foreach ($post['Setting'] as $item) {
                $setting = $this->findModel($item['id']);
                if (is_array($item['value'])) {
                    $item['value'] = json_encode($item['value'], JSON_UNESCAPED_UNICODE);
                }
                $setting->value = $item['value'];
                $setting->save();

                if (isset($post['cache_key'])) {
                    Yii::$app->cache->delete($post['cache_key']);
                }
                if (isset($post['cache_dependency'])) {
                    TagDependency::invalidate(Yii::$app->cache, $post['cache_dependency']);
                }
            }
            if (isset($post['back_url'])) {
                return $this->redirect([$post['back_url']]);
            }
        }

        return $this->redirect(['/admin/default']);
    }

    public function actionBase()
    {
        $cacheSettings = Setting::find()->where(['like', 'option', 'cache.'])->all();
        $cacheSettings = Setting::prepareSettingsForForms($cacheSettings);

        return $this->render('base', [
            'cacheSettings' => $cacheSettings
        ]);
    }

    public function actionProxy()
    {
        $settings = Setting::find()->where(['like', 'option', 'proxy.'])->all();
        $settings = Setting::prepareSettingsForForms($settings);

        return $this->render('proxy', [
            'settings' => $settings
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionClearCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', 'Кеш очищен');

        return $this->redirect(['setting/base']);
    }

    public function actionGenerateToken()
    {
        $token = md5(Yii::$app->request->remoteIP . microtime());
        $setting = Setting::findOne(['option' => 'cron.token']);
        $setting->value = $token;
        $setting->save();

        $post = Yii::$app->request->post();
        if (isset($post['cache_key'])) {
            Yii::$app->cache->delete($post['cache_key']);
        }
        if (isset($post['cache_dependency'])) {
            TagDependency::invalidate(Yii::$app->cache, $post['cache_dependency']);
        }
        if (isset($post['back_url'])) {
            return $this->redirect([$post['back_url']]);
        }

        return $token;
    }
}
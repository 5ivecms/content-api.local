<?php

namespace backend\controllers;

use common\components\Proxy\Ping;
use Yii;
use common\models\Proxy;
use common\models\ProxySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProxyController implements the CRUD actions for Proxy model.
 */
class ProxyController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Proxy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProxySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Proxy model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Proxy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Proxy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateList()
    {
        $post = Yii::$app->request->post();
        if ($post && $post['Proxy']['list']) {
            $proxyList = [];
            $list = explode("\r\n", $post['Proxy']['list']);
            foreach ($list as $item) {
                $proxy = [];
                $proxyParts = explode(':', $item);
                if (count($proxyParts) < 2) {
                    continue;
                }
                $proxy['ip'] = $proxyParts[0];
                $proxy['port'] = $proxyParts[1];
                $proxyList[] = $proxy;
            }

            foreach ($proxyList as $proxy) {
                $model = new Proxy();
                $model->ip = $proxy['ip'];
                $model->port = $proxy['port'];
                $model->type = $post['Proxy']['type'];
                $model->protocol = $post['Proxy']['protocol'];
                $model->login = (isset($post['Proxy']['login']) ? $post['Proxy']['login'] : '');
                $model->password = (isset($post['Proxy']['password']) ? $post['Proxy']['password'] : '');
                $model->save();
            }

            Yii::$app->session->setFlash('success', 'Прокси добавлены');
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Proxy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Proxy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteSelected()
    {
        if (!isset($_POST['ids'])) {
            return $this->redirect(['proxy/index']);
        }

        $proxies = Proxy::find()->where(['id' => $_POST['ids']])->all();
        foreach ($proxies as $proxy) {
            $proxy->delete();
        }

        Yii::$app->session->setFlash('success', 'Прокси удалены');

        return $this->redirect(['proxy/index']);
    }

    public function actionDeleteAll()
    {
        Proxy::deleteAll();

        Yii::$app->session->setFlash('success', 'Прокси удалены');

        return $this->redirect(['proxy/index']);
    }

    public function actionPingSelected()
    {
        if (!isset($_POST['ids'])) {
            return $this->redirect(['proxy/index']);
        }

        set_time_limit(0);

        $proxies = Proxy::find()->where(['id' => $_POST['ids']])->all();
        foreach ($proxies as $proxy) {
            $curlInfo = Ping::get('http://youtube.com/', $proxy->ip, $proxy->port, $proxy->type, $proxy->protocol, $proxy->login, $proxy->password);
            $this->updateProxyInfo($proxy, $curlInfo);
            sleep(1);
        }

        Yii::$app->session->setFlash('success', 'Прокси обновлены');

        return $this->redirect(['index']);
    }

    public function actionPingAll()
    {
        set_time_limit(0);

        $proxies = Proxy::find()->all();
        foreach ($proxies as $proxy) {
            $curlInfo = Ping::get('http://youtube.com/', $proxy->ip, $proxy->port, $proxy->type, $proxy->protocol, $proxy->login, $proxy->password);
            $this->updateProxyInfo($proxy, $curlInfo);
            sleep(1);
        }

        Yii::$app->session->setFlash('success', 'Прокси обновлены');

        return $this->redirect(['index']);
    }

    public function actionPing($id)
    {
        $proxy = $this->findModel($id);
        $curlInfo = Ping::get('https://youtube.com/', $proxy->ip, $proxy->port, $proxy->type, $proxy->protocol, $proxy->login, $proxy->password);
        $result = $this->updateProxyInfo($proxy, $curlInfo);
        if ($result) {
            Yii::$app->session->setFlash('success', 'Прокси ' . $proxy->ip . ':' . $proxy->port . ' обновлен');
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $post = Yii::$app->request->post();
        if ($post && isset($post['status'], $post['id'])) {
            $status = ($post['status'] === 'true') ? '1' : '0';
            $id = $post['id'];
            $model = Proxy::findOne($id);
            $model->status = $status;
            $model->save();
        }
    }

    public function updateProxyInfo(Proxy $proxy, $curlInfo)
    {
        $proxy->totalTime = (int) ($curlInfo['total_time'] * 1000);
        $proxy->connectTime = (int) ($curlInfo['connect_time'] * 1000);
        $proxy->pretransferTime = (int) ($curlInfo['pretransfer_time'] * 1000);
        if ($curlInfo['http_code'] === 302) {
            $proxy->redirected = 1;
            $proxy->status = 0;
        }

        return $proxy->save();
    }

    public function actionResetStats()
    {
        $proxies = Proxy::find()->all();
        foreach ($proxies as $proxy) {
            $proxy->totalTime = Proxy::DEFAULT_SATS['totalTime'];
            $proxy->connectTime = Proxy::DEFAULT_SATS['connectTime'];
            $proxy->pretransferTime = Proxy::DEFAULT_SATS['pretransferTime'];
            $proxy->countCaptcha = Proxy::DEFAULT_SATS['countCaptcha'];
            $proxy->countErrors = Proxy::DEFAULT_SATS['countErrors'];
            $proxy->redirected = Proxy::DEFAULT_SATS['redirected'];
            $proxy->status = Proxy::DEFAULT_SATS['status'];
            $proxy->save();
        }

        Yii::$app->session->setFlash('success', 'Статистика сброшена');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Proxy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proxy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Proxy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

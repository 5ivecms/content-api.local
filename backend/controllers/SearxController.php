<?php

namespace backend\controllers;

use Yii;
use common\models\Searx;
use common\models\SearxSearch;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

/**
 * SearxController implements the CRUD actions for Searx model.
 */
class SearxController extends BaseController
{
    /**
     * Lists all Searx models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Searx model.
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
     * Creates a new Searx model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Searx();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateList()
    {
        $model = new Searx();

        $post = Yii::$app->request->post();
        if ($post) {
            $modelName = StringHelper::basename(get_class($model));
            $list = $post[$modelName]['list'];
            $list = explode("\r\n", $list);
            $list = array_unique($list);

            foreach ($list as $item) {
                if (!empty($item)) {
                    $currentModel = new Searx();
                    $currentModel->host = trim($item);
                    if (!$currentModel->validate()) {
                        continue;
                    }
                    $currentModel->save();
                }
            }

            Yii::$app->session->setFlash('success', 'Инстансы добавлены');
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Searx model.
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
     * Deletes an existing Searx model.
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

        $searxs = Searx::find()->where(['id' => $_POST['ids']])->all();
        foreach ($searxs as $searx) {
            $searx->delete();
        }

        Yii::$app->session->setFlash('success', 'Хосты удалены');

        return $this->redirect(['searx/index']);
    }

    /**
     * Finds the Searx model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Searx the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Searx::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateBlockedStatus()
    {
        $post = Yii::$app->request->post();
        if ($post && isset($post['status'], $post['id'])) {
            $status = $post['status'] === 'true' ? 1 : 0;
            $id = $post['id'];
            $model = Searx::findOne($id);
            $model->is_blocked = $status;
            $model->save();
        }
    }

    public function actionResetBlockedStatus()
    {
        foreach (Searx::find()->all() as $searx) {
            $searx->is_blocked = Searx::IS_NOT_BLOCKED;
            $searx->save();
        }

        Yii::$app->session->setFlash('success', 'Статусы сброшены');

        return $this->redirect(['searx/index']);
    }
}

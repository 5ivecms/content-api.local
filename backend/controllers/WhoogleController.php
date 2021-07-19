<?php

namespace backend\controllers;

use Yii;
use common\models\Whoogle;
use common\models\WhoogleSearch;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WhoogleController implements the CRUD actions for Whoogle model.
 */
class WhoogleController extends BaseController
{
    /**
     * Lists all Whoogle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WhoogleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Whoogle model.
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
     * Creates a new Whoogle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Whoogle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateList()
    {
        $model = new Whoogle();

        $post = Yii::$app->request->post();
        if ($post) {
            $modelName = StringHelper::basename(get_class($model));
            $list = $post[$modelName]['list'];
            $list = explode("\r\n", $list);
            $list = array_unique($list);

            foreach ($list as $item) {
                if (!empty($item)) {
                    $currentModel = new Whoogle();
                    $currentModel->host = trim($item);
                    if (!$currentModel->validate()) {
                        continue;
                    }
                    $currentModel->save();
                }
            }

            Yii::$app->session->setFlash('success', 'Хосты добавлены');
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Whoogle model.
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
     * Deletes an existing Whoogle model.
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

        $whoogles = Whoogle::find()->where(['id' => $_POST['ids']])->all();
        foreach ($whoogles as $whoogle) {
            $whoogle->delete();
        }

        Yii::$app->session->setFlash('success', 'Хосты удалены');

        return $this->redirect(['index']);
    }
    /**
     * Finds the Whoogle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Whoogle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Whoogle::findOne($id)) !== null) {
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
            $model = Whoogle::findOne($id);
            $model->is_blocked = $status;
            $model->save();
        }
    }

    public function actionResetBlockedStatus()
    {
        foreach (Whoogle::find()->all() as $searx) {
            $searx->is_blocked = Whoogle::IS_NOT_BLOCKED;
            $searx->save();
        }

        Yii::$app->session->setFlash('success', 'Статусы сброшены');

        return $this->redirect(['whoogle/index']);
    }
}

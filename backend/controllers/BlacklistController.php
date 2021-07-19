<?php

namespace backend\controllers;

use Yii;
use common\models\Blacklist;
use common\models\BlacklistSearch;
use yii\helpers\StringHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlacklistController implements the CRUD actions for Blacklist model.
 */
class BlacklistController extends Controller
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
     * Lists all Blacklist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlacklistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blacklist model.
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
     * Creates a new Blacklist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blacklist();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateList()
    {
        $model = new Blacklist();

        $post = Yii::$app->request->post();
        if ($post) {
            $modelName = StringHelper::basename(get_class($model));
            $list = $post[$modelName]['list'];
            $list = explode("\r\n", $list);
            $list = array_unique($list);

            foreach ($list as $item) {
                if (!empty($item)) {
                    $currentModel = new Blacklist();
                    $currentModel->domain = trim($item);
                    if (!$currentModel->validate()) {
                        continue;
                    }
                    $currentModel->save();
                }
            }

            Yii::$app->session->setFlash('success', 'Черный список обновлен');
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Blacklist model.
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
     * Deletes an existing Blacklist model.
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

        foreach (Blacklist::find()->where(['id' => $_POST['ids']])->all() as $item) {
            $item->delete();
        }

        Yii::$app->session->setFlash('success', 'Сайты удалены');

        return $this->redirect(['blacklist/index']);
    }

    /**
     * Finds the Blacklist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blacklist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blacklist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

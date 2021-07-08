<?php

namespace backend\controllers;

use Yii;
use common\models\Useragent;
use common\models\UseragentSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UseragentController implements the CRUD actions for Useragent model.
 */
class UseragentController extends BaseController
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
     * Lists all Useragent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UseragentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Useragent model.
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
     * Creates a new Useragent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Useragent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Юзерагент добавлен');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateList()
    {
        $post = Yii::$app->request->post();
        if ($post) {
            $list = $post['Useragent']['list'];
            $list = explode("\r\n", $list);
            foreach ($list as $item) {
                if (!empty($item)) {
                    $model = new Useragent();
                    $model->useragent = $item;
                    $model->save();
                }
            }
            Yii::$app->session->setFlash('success', 'Юзерагенты добавлены');
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Useragent model.
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
     * Deletes an existing Useragent model.
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
            return $this->redirect(['useragent/index']);
        }

        $useragents = Useragent::find()->where(['id' => $_POST['ids']])->all();
        foreach ($useragents as $useragent) {
            $useragent->delete();
        }

        Yii::$app->session->setFlash('success', 'Юзерагенты удалены');

        return $this->redirect(['useragent/index']);
    }

    /**
     * Finds the Useragent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Useragent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Useragent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

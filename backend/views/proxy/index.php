<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProxySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Прокси';
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_gridview', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider
]);
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UseragentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список юзерагентов';
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_gridview', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider
]);
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Useragent */

$this->title = 'Редактировать: ' . $model->useragent;
$this->params['breadcrumbs'][] = ['label' => 'Юзерагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->useragent, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => \kartik\detail\DetailView::MODE_EDIT
]);

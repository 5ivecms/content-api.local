<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Blacklist */

$this->title = 'Редактировать: ' . $model->domain;
$this->params['breadcrumbs'][] = ['label' => 'Черный список', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->domain, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => DetailView::MODE_EDIT
]);
<?php

/* @var $this yii\web\View */
/* @var $model common\models\Searx */

use kartik\detail\DetailView;

$this->title = 'Редактировать: ' . $model->host;
$this->params['breadcrumbs'][] = ['label' => 'ИНстансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->host, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => DetailView::MODE_EDIT
]);

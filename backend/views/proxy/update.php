<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Proxy */

$this->title = 'Редактировать: ' . $model->ip . ':' . $model->port;
$this->params['breadcrumbs'][] = ['label' => 'Прокси', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ip . ':' . $model->port, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => DetailView::MODE_EDIT
]);
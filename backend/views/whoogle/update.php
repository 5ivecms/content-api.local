<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Whoogle */

$this->title = 'Редактировать: ' . $model->host;
$this->params['breadcrumbs'][] = ['label' => 'Whoogle хосты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->host, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => DetailView::MODE_EDIT
]);

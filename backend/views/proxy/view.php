<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Proxy */

$this->title = $model->ip . ':' . $model->port;
$this->params['breadcrumbs'][] = ['label' => 'Прокси', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => DetailView::MODE_VIEW
]);

<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Searx */
/* @var $mode string */

$this->title = $model->host;
$this->params['breadcrumbs'][] = ['label' => 'Инстансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => DetailView::MODE_VIEW
]);

<?php

/* @var $this yii\web\View */
/* @var $model common\models\Useragent */

$this->title = $model->useragent;
$this->params['breadcrumbs'][] = ['label' => 'Юзерагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

echo $this->render('_detailview', [
    'model' => $model,
    'mode' => \kartik\detail\DetailView::MODE_VIEW
]);

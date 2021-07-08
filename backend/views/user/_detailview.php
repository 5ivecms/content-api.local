<?php

use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $model common\models\User */
/* @var $mode string */

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'username',
        'email',
        'access_token',
    ],
    'striped' => false,
    'fadeDelay' => 100,
    'panel' => [
        'heading' => '<h3 class="card-title">Информация</h3>',
        'type' => DetailView::TYPE_DEFAULT,
    ],
    'vAlign' => DetailView::ALIGN_TOP,
    'formOptions' => ['action' => Url::to(['update', 'id' => $model->id])], // your action to delete
    'deleteOptions' => ['url' => Url::to(['delete', 'id' => $model->id])],
    'mode' => $mode
]);
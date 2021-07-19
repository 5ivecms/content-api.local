<?php

use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $model common\models\Blacklist */
/* @var $mode string */

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'columns' => [
                [
                    'attribute' => 'id',
                    'displayOnly' => true,
                    'valueColOptions' => ['style' => 'width:100%']
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'domain',
                    'valueColOptions' => ['style' => 'width:100%']
                ],
            ],
        ],
    ],
    'striped' => false,
    'fadeDelay' => 100,
    'panel' => [
        'heading' => '<h3 class="card-title">Информация</h3>',
        'type' => DetailView::TYPE_DEFAULT,
    ],
    'vAlign' => DetailView::ALIGN_TOP,
    'formOptions' => ['action' => Url::to(['update', 'id' => $model->id])],
    'deleteOptions' => ['url' => Url::to(['delete', 'id' => $model->id])],
    'mode' => $mode
]);
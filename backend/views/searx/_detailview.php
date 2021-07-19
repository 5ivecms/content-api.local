<?php

use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $model common\models\Searx */
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
                    'attribute' => 'host',
                    'valueColOptions' => ['style' => 'width:100%']
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'is_blocked',
                    'format' => 'raw',
                    'value' => $model->is_blocked ? '<span class="badge badge-danger">Да</span>' : '<span class="badge badge-success">Нет</span>',
                    'type' => DetailView::INPUT_SWITCH,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'onText' => 'Да',
                            'offText' => 'Нет',
                            'onColor' => 'danger',
                            'offColor' => 'success',
                        ]
                    ],
                    'valueColOptions' => ['style' => 'width:100%']
                ],
            ],
        ],
    ],
    'striped' => false,
    'fadeDelay' => 100,
    'panel' => [
        'heading' => '<h3 class="card-title">Информация об инстансе</h3>',
        'type' => DetailView::TYPE_DEFAULT,
    ],
    'vAlign' => DetailView::ALIGN_TOP,
    'formOptions' => ['action' => Url::to(['update', 'id' => $model->id])],
    'deleteOptions' => ['url' => Url::to(['delete', 'id' => $model->id])],
    'mode' => $mode
]);
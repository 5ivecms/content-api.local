<?php

use kartik\detail\DetailView;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var pheme\settings\models\Setting $model
 * @var string $mode
 */

?>

<?= DetailView::widget(
    [
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
                        'attribute' => 'type',
                        'format' => 'raw',
                        'valueColOptions' => ['style' => 'width:100%'],
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => $model->getTypes(),
                            'options' => ['placeholder' => 'Select ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'section',
                        'valueColOptions' => ['style' => 'width:100%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'key',
                        'valueColOptions' => ['style' => 'width:100%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'value',
                        'valueColOptions' => ['style' => 'width:100%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'active',
                        'label' => 'Active',
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'onText' => 'Yes',
                                'offText' => 'No',
                            ]
                        ],
                        'value' => $model->active ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>',
                        'valueColOptions' => ['style' => 'width:100%']
                    ],
                ]
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'created',
                        'displayOnly' => true,
                        'format' => 'datetime',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'modified',
                        'displayOnly' => true,
                        'format' => 'datetime',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
        ],
        'striped' => false,
        'fadeDelay' => 100,
        'panel' => [
            'heading' => '<h3 class="card-title">Информация о настройке</h3>',
            'type' => DetailView::TYPE_DEFAULT,
        ],
        'vAlign' => DetailView::ALIGN_TOP,
        'formOptions' => ['action' => Url::to(['update', 'id' => $model->id])],
        'deleteOptions' => ['url' => Url::to(['delete', 'id' => $model->id])],
        'mode' => $mode
    ]
) ?>
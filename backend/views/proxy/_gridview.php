<?php

use common\models\Proxy;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $searchModel common\models\ProxySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$selectedOptions = [
    Url::to(['proxy/ping-selected']) => 'Пинг выбранных',
    Url::to(['proxy/ping-all']) => 'Пинг всех',
    Url::to(['proxy/delete-selected']) => 'Удалить выбранные',
    Url::to(['proxy/delete-all']) => 'Удалить все',
    Url::to(['proxy/reset-stats']) => 'Сбросить статистику',
];

$proxyTypes = [];
foreach (Proxy::TYPES as $k => $type) {
    $proxyTypes[$k] = $k;
}
$proxyProtocols = [];
foreach (Proxy::PROTOCOLS as $protocol) {
    $proxyProtocols[$protocol] = $protocol;
}

echo GridView::widget([
    'id' => 'proxy-gridview',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $class = $model->redirected == 1 ? 'table-danger' : '';
        return [
            'key' => $key,
            'index' => $index,
            'class' => $class
        ];
    },
    'columns' => [
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::BS_TABLE_INFO,
            'checkboxOptions' => function ($model) {
                return ['value' => $model->id];
            },
        ],

        [
            'attribute' => 'ip',
            'label' => 'IP',
            'headerOptions' => ['style' => 'min-width:171px;'],
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->ip;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Proxy::find()->distinct()->asArray()->all(), 'ip', 'ip'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Выбрать IP', 'multiple' => false],
        ],
        'port',
        [
            'attribute' => 'type',
            'label' => 'Тип',
            'headerOptions' => ['style' => 'min-width:143px;width:143px'],
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->type;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $proxyTypes,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Тип прокси', 'multiple' => false],
        ],
        [
            'attribute' => 'protocol',
            'label' => 'Протокол',
            'headerOptions' => ['style' => 'min-width:171px;'],
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->protocol;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $proxyProtocols,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Протокол', 'multiple' => false],
        ],
        'login',
        'password',
        [
            'attribute' => 'totalTime',
            'label' => 'Total Time',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->totalTime . 'ms';
            },
            'contentOptions' => function ($data) {
                if ($data->totalTime <= \common\models\Setting::getProxySettings()['ping']) {
                    $class = 'table-success';
                } else {
                    $class = 'table-danger';
                }
                return ['class' => $class];
            },
        ],
        [
            'attribute' => 'connectTime',
            'label' => 'Connect Time',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->connectTime . 'ms';
            },
            'contentOptions' => function ($data) {
                if ($data->connectTime <= \common\models\Setting::getProxySettings()['ping']) {
                    $class = 'table-success';
                } else {
                    $class = 'table-danger';
                }
                return ['class' => $class];
            },
        ],
        [
            'attribute' => 'countCaptcha',
            'label' => 'Капчи',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->countCaptcha;
            },
            'contentOptions' => function ($data) {
                if ($data->countCaptcha == '0') {
                    $class = 'table-success';
                } else {
                    $class = 'table-danger';
                }
                return ['class' => $class];
            },
        ],
        [
            'attribute' => 'countErrors',
            'label' => 'Ошибок',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->countErrors;
            },
            'contentOptions' => function ($data) {
                if ($data->countErrors == '0') {
                    $class = 'table-success';
                } else {
                    $class = 'table-danger';
                }
                return ['class' => $class];
            },
        ],
        [
            'attribute' => 'redirected',
            'label' => 'Редирект',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($model) {
                return ($model->redirected == '1') ? '<span class="badge badge-danger">Да</span>' : '<span class="badge badge-success">Нет</span>';
            },
            'contentOptions' => function ($data) {
                if ($data->redirected == '0') {
                    $class = 'table-success text-center';
                } else {
                    $class = 'table-danger text-center';
                }
                return ['class' => $class];
            },
        ],
        [
            'attribute' => 'status',
            'label' => 'Статус',
            'encodeLabel' => false,
            'headerOptions' => ['style' => 'min-width:170px'],
            'hAlign' => GridView::ALIGN_CENTER,
            'format' => 'raw',
            'value' => function ($model) {
                return SwitchInput::widget([
                    'name' => 'status',
                    'value' => $model->status,
                    'pluginEvents' => [
                        'switchChange.bootstrapSwitch' => "function(e){sendRequest(e.currentTarget.checked, $model->id);}"
                    ],
                    'pluginOptions' => [
                        'size' => 'mini',
                        'onColor' => 'success',
                        'offColor' => 'danger',
                        'onText' => 'Вкл',
                        'offText' => 'Выкл',
                    ],
                    'labelOptions' => ['style' => 'font-size: 12px;'],
                ]);
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [1 => 'Активный', 0 => 'Не активный'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Статус', 'multiple' => false],
        ],
        [
            'width' => '90px',
            'header' => '',
            'headerOptions' => ['style' => 'width:90px'],
            'class' => 'kartik\grid\ActionColumn'
        ],
    ],
    'toolbar' => [
        [
            'content' =>
                Html::a('<i class="fas fa-plus"></i>', ['create'], [
                    'class' => 'btn btn-success',
                    'title' => 'Добавить прокси'
                ]) . ' ' .
                Html::a('<i class="fas fa-redo"></i>', ['index'], [
                    'class' => 'btn btn-outline-secondary',
                    'title' => 'Обновить таблицу',
                    'data-pjax' => 1,
                ]),
            'options' => ['class' => 'btn-group mr-2']
        ],
        '{export}',
        '{toggleData}',
    ],
    'toggleDataContainer' => ['class' => 'btn-group'],
    'exportContainer' => ['class' => 'btn-group mr-2'],
    'responsive' => true,
    'panel' => [
        'heading' => '<h3 class="card-title">Список прокси</h3>',
        'type' => 'default',
        'after' => false,
        'before' =>
            '<div class="form-inline">' .
            '<b class="d-inline-block mr-3">Действие: </b>' .
            Html::dropDownList('action', null, $selectedOptions, ['id' => 'action', 'class' => 'form-control mr-2']) .
            '<button id="actionBtn" type="submit" class="btn btn-primary mr-4">Выполнить</button>' .
            '</div>'
    ],
]);

$js = <<< JS
$(document).on('click', '#actionBtn', function (event) {
    event.preventDefault();

    var grid = $(this).data('grid');
    var Ids = $('#proxy-gridview').yiiGridView('getSelectedRows');
    var status = $(this).data('status');
    var action = $('#action').val();
    var actionText = $('#action').find('option:selected').text();

    if (confirm('Точно ' + actionText + ' выбранные?')) {
        $.ajax({
            type: 'POST',
            url: action,
            data: {ids: Ids},
            dataType: 'JSON',
            success: function (resp) {
                if (resp.success) {
                    alert(resp.msg);
                }
                location.reload();
            }
        });
    }
});
JS;

$js2 = <<< JS
    function sendRequest(status, id) {
        $.ajax({
            url: '/admin/proxy/update-status',
            method: 'post',
            data: {status: status, id: id},
            success:function(data) {
            },
            error:function(jqXhr,status,error) {
            }
        });
    }
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
$this->registerJs($js2, \yii\web\View::POS_READY);

?>

<style>
    #proxy-gridview-container td .form-group {
        margin: 0;
    }
</style>

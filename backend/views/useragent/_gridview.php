<?php

use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $searchModel common\models\UseragentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$selectedOptions = [
    Url::to(['useragent/delete-selected']) => 'Удалить'
];

echo GridView::widget([
    'id' => 'useragent-gridview',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::BS_TABLE_INFO,
            'checkboxOptions' => function ($model) {
                return ['value' => $model->id];
            },
        ],

        'id',
        'useragent:ntext',

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
                    'title' => 'Добавить юзерагент'
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
        'heading' => '<h3 class="card-title">Список юзерагентов</h3>',
        'type' => 'default',
        'after' => false,
        'before' =>
            '<div class="form-inline">' .
            '<b class="d-inline-block mr-3">С выбранными: </b>' .
            Html::dropDownList('action', null, $selectedOptions, ['id' => 'action', 'class' => 'form-control mr-2']) .
            '<button id="actionBtn" type="submit" class="btn btn-primary mr-4">Выполнить</button>' .
            '</div>'
    ],
]);

$js = "
$(document).on('click', '#actionBtn', function (event) {
    event.preventDefault();

    var grid = $(this).data('grid');
    var Ids = $('#useragent-gridview').yiiGridView('getSelectedRows');
    var status = $(this).data('status');
    var action = $('#action').val();
    var actionText = $('#action').find('option:selected').text();

    if (Ids.length > 0) {
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
    } else {
        alert('Нет выбранных строк');
    }
});
";

$this->registerJs($js, \yii\web\View::POS_READY);
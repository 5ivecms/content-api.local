<?php

use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Searx инстансы';
$this->params['breadcrumbs'][] = $this->title;

$selectedOptions = [
    Url::to(['searx/reset-blocked-status']) => 'Сбросить статус блокировки',
    Url::to(['searx/delete-selected']) => 'Удалить выбранные',
];
?>

<div class="searx-index">

    <?= GridView::widget([
        'id' => 'searx-gridview',
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
            ['class' => 'kartik\grid\SerialColumn'],
            'host',
            [
                'attribute' => 'is_blocked',
                'width' => '100px',
                'label' => 'Заблокирован',
                'encodeLabel' => false,
                'headerOptions' => ['style' => 'min-width:170px'],
                'hAlign' => GridView::ALIGN_CENTER,
                'format' => 'raw',
                'value' => function ($model) {
                    return SwitchInput::widget([
                        'name' => 'is_blocked',
                        'value' => $model->is_blocked,
                        'pluginEvents' => [
                            'switchChange.bootstrapSwitch' => "function(e){sendRequest(e.currentTarget.checked, $model->id);}"
                        ],
                        'pluginOptions' => [
                            'size' => 'mini',
                            'onColor' => 'danger',
                            'offColor' => 'success',
                            'onText' => 'Да',
                            'offText' => 'Нет',
                        ],
                        'labelOptions' => ['style' => 'font-size: 12px;'],
                    ]);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => [0 => 'Нет', 1 => 'Да'],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Блокировка', 'multiple' => false],
            ],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['create'], [
                        'class' => 'btn btn-success',
                        'title' => 'Добавить searx инстанс'
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
            'heading' => '<h3 class="card-title">Список хостов</h3>',
            'type' => 'default',
            'after' => false,
            'before' =>
                '<div class="form-inline">' .
                Html::dropDownList('action', null, $selectedOptions, ['id' => 'action', 'class' => 'form-control mr-2']) .
                '<button id="actionBtn" type="submit" class="btn btn-primary mr-4">Выполнить</button>' .
                '</div>'
        ],
    ]); ?>
</div>

<?php
$js = <<< JS
$(document).on('click', '#actionBtn', function (event) {
    event.preventDefault();

    var grid = $(this).data('grid');
    var Ids = $('#searx-gridview').yiiGridView('getSelectedRows');
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
            url: '/admin/searx/update-blocked-status',
            method: 'post',
            data: {status: status, id: id},
            success:function(data) {
                console.log(data)
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
    #searx-gridview-container td .form-group {
        margin: 0;
    }
</style>

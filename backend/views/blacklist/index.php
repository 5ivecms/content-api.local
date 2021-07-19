<?php

use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlacklistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Черный список';
$this->params['breadcrumbs'][] = $this->title;

$selectedOptions = [
    Url::to(['blacklist/delete-selected']) => 'Удалить выбранные',
];
?>

<div class="blacklist-index">

    <?= GridView::widget([
        'id' => 'blacklist-gridview',
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
            'domain',
            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['create'], [
                        'class' => 'btn btn-success',
                        'title' => 'Добавить сайт'
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
            'heading' => '<h3 class="card-title">Список сайтов</h3>',
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
    var Ids = $('#blacklist-gridview').yiiGridView('getSelectedRows');
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

$this->registerJs($js, \yii\web\View::POS_READY);

?>
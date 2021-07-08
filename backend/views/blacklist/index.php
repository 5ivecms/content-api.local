<?php

use kartik\grid\GridView;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlacklistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Черный список';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blacklist-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
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
        ],
    ]); ?>


</div>

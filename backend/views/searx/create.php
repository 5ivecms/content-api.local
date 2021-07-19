<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Searx */

$this->title = 'Добавить searx инстанс';
$this->params['breadcrumbs'][] = ['label' => 'Searxes инстансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="searx-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

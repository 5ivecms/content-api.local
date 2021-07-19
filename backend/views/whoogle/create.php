<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Whoogle */

$this->title = 'Добавить хосты Whoogle';
$this->params['breadcrumbs'][] = ['label' => 'Whoogle хосты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="whoogle-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

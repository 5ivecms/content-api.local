<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Proxy */

$this->title = 'Добавить прокси';
$this->params['breadcrumbs'][] = ['label' => 'Прокси', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="proxy-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

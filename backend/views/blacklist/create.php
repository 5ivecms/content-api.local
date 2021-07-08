<?php

/* @var $this yii\web\View */
/* @var $model common\models\Blacklist */

$this->title = 'Добавить сайт';
$this->params['breadcrumbs'][] = ['label' => 'Черный список', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blacklist-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

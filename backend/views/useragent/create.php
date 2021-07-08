<?php

/* @var $this yii\web\View */
/* @var $model common\models\Useragent */

$this->title = 'Добавить юзерагент';
$this->params['breadcrumbs'][] = ['label' => 'Юзерагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="useragent-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

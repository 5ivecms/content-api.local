<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Blacklist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blacklist-form">

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Добавить списком</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'domain')->textarea(['rows' => 10, 'placeholder' => 'Список доменов. Каждый с новой строки'])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Добавить</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'domain')->textInput(['maxlength' => true, 'placeholder' => 'yandex.ru'])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>

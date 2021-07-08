<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Useragent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Добавить</h3>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'useragent')->textInput(['maxlength' => true, 'placeholder' => 'Юзерагент']) ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Добавить списком</h3>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['action' => ['create-list']]); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'list')->textarea(['maxlength' => true, 'placeholder' => 'Список юзерагентов', 'rows' => 7]) ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
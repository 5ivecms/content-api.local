<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Whoogle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="searx-form">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Добавить</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'host')->textInput(['maxlength' => true, 'placeholder' => 'https://whoogle.org'])->label(false) ?>

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
                    <?php $form = ActiveForm::begin(['action' => ['whoogle/create-list']]); ?>

                    <?= $form->field($model, 'list')->textarea(['rows' => 10, 'placeholder' => 'https://whoogle.org'])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

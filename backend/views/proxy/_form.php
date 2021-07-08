<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Proxy */
/* @var $form yii\widgets\ActiveForm */

$proxyTypes = [];
$proxyProtocols = [];

foreach (\common\models\Proxy::TYPES as $k => $type) {
    $proxyTypes[$k] = $k;
}
foreach (\common\models\Proxy::PROTOCOLS as $protocol) {
    $proxyProtocols[$protocol] = $protocol;
}

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
                    <div class="col-md-6">
                        <?= $form->field($model, 'ip')->textInput(['maxlength' => true, 'placeholder' => '0.0.0.0']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'port')->textInput(['maxlength' => true, 'placeholder' => '0000']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'type')->dropDownList($proxyTypes, []); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'protocol')->dropDownList($proxyProtocols, []); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'login')->textInput(['maxlength' => true, 'placeholder' => 'Логин']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>
                    </div>
                </div>
                <p class="small text-secondary">При добавлении прокси с протоколом IPv6 подразумевается, что в качествестве шлюза к IPv6 будет прокси с протоколом IPv4, т.е. IPv4 => IPv6.</p>
                <div class="form-group">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
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
                    <div class="col-12 col-md-12">
                        <?= $form->field($model, 'list')->textarea(['placeholder' => 'Пример: 0.0.0.0:00. Каждый с новой строки', 'rows' => 7]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'type')->dropDownList($proxyTypes, []); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'protocol')->dropDownList($proxyProtocols, []); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'login')->textInput(['maxlength' => true, 'placeholder' => 'Логин']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>
                    </div>
                </div>
                <p class="small text-secondary">При добавлении прокси с протоколом IPv6 подразумевается, что в качествестве шлюза к IPv6 будет прокси с протоколом IPv4, т.е. IPv4 => IPv6.</p>
                <div class="form-group">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
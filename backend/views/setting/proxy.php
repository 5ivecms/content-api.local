<?php

use common\components\Proxy\APIServices;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $settings array */

$this->title = 'Настройки прокси';
$proxyServices = [];
foreach (APIServices::SERVICES as $service) {
    $proxyServices[$service] = $service;
}
?>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Базовые</h3>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['action' => ['setting/update']]); ?>
                <?= Html::input('hidden', 'back_url', 'setting/proxy') ?>
                <?= Html::input('hidden', 'cache_key', 'settings.proxy') ?>
                <?= Html::input('hidden', 'cache_dependency', 'settings.proxy') ?>

                <div class="form-group">
                    <?= $form->field($settings['proxy.enabled'], "[proxy.enabled]value")
                        ->checkbox(['label' => $settings['proxy.enabled']['label']])
                    ?>
                    <?= $form->field($settings['proxy.enabled'], "[proxy.enabled]id")->hiddenInput()->label(false) ?>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <?= $form->field($settings['proxy.timeout'], "[proxy.timeout]value")
                                    ->textInput(['type' => 'number'])
                                    ->label($settings['proxy.timeout']['label'])
                                ?>
                                <?= $form->field($settings['proxy.timeout'], "[proxy.timeout]id")->hiddenInput()->label(false) ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <?= $form->field($settings['proxy.ping'], "[proxy.ping]value")
                                    ->textInput(['type' => 'number'])
                                    ->label($settings['proxy.ping']['label'])
                                ?>
                                <?= $form->field($settings['proxy.ping'], "[proxy.ping]id")->hiddenInput()->label(false) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-12 col-md-6">
    <div class="card">
        <div class="card-header bg-light">
            <h3 class="card-title">Кеширование</h3>
        </div>
        <div class="card-body">
            <b>Размер кеша:</b> <?= \common\models\Tools::show_size(Yii::getAlias('@common') . '/runtime/cache', true) ?>
            <br />
            <br />
            <?php $form = ActiveForm::begin(['action' => '/admin/setting/update']); ?>
            <?= Html::input('hidden', 'back_url', 'setting/base') ?>
            <?= Html::input('hidden', 'cache_key', 'cache.duration') ?>
            <?= Html::input('hidden', 'cache_dependency', 'settings.cache') ?>

            <?= $form->field($settings['cache.duration'], "[cache.duration]value")
                ->textInput(['type' => 'number'])
                ->label($settings['cache.duration']['label'])
            ?>
            <?= $form->field($settings['cache.duration'], "[cache.duration]id")->hiddenInput()->label(false) ?>

            <p class="small text-secondary">
                86400 - сутки <br />
                172800 - 2-е суток <br />
                259200 - 3-е суток <br />
                345600 - 4 суток <br />
                432000 - 5 суток <br />
            </p>

            <div class="row">
                <div class="col-6">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <div class="col-6 text-right">
                    <a href="<?= Url::to(['setting/clear-cache']) ?>" class="btn btn-danger">Очистить кеш</a>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pheme\settings\Module;

/**
 * @var yii\web\View $this
 * @var pheme\settings\models\Setting $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="setting-form">

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Форма</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'section')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'active')->checkbox(['value' => 1]) ?>

                    <?=
                    $form->field($model, 'type')->dropDownList(
                        $model->getTypes()
                    )->hint(Module::t('settings', 'Change at your own risk')) ?>

                    <div class="form-group">
                        <?=
                        Html::submitButton(
                            $model->isNewRecord ? Module::t('settings', 'Создать') :
                                Module::t('settings', 'Сохранить'),
                            [
                                'class' => $model->isNewRecord ?
                                    'btn btn-success' : 'btn btn-primary'
                            ]
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>

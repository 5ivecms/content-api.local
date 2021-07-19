<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use pheme\settings\Module;

/**
 * @var yii\web\View $this
 * @var pheme\settings\models\Setting $model
 */

$this->title = Module::t(
        'settings',
        'Update {modelClass}: ',
        [
            'modelClass' => Module::t('settings', 'Setting'),
        ]
    ) . ' ' . $model->section. '.' . $model->key;

$this->params['breadcrumbs'][] = ['label' => Module::t('settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('settings', 'Update');

?>

<div class="setting-update">

    <?= $this->render('_detailview', [
        'model' => $model,
        'mode' => \kartik\detail\DetailView::MODE_EDIT
    ]) ?>

</div>

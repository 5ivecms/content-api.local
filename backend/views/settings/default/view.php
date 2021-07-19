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

$this->title = $model->section . '.' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Module::t('settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-view">

    <?= $this->render('_detailview', [
        'model' => $model,
        'mode' => \kartik\detail\DetailView::MODE_VIEW
    ]) ?>

</div>

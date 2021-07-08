<?php

/** @var \yii\web\View $this */
/** @var string $directoryAsset */

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?= \yii\helpers\Html::a('<img class="brand-image img-circle elevation-3" src="' . ($directoryAsset . '/img/AdminLTELogo.png') . '" alt="APP"><span class="brand-text font-weight-light">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'brand-link']) ?>
    <div class="sidebar">
        <nav class="mt-2">
            <?= dmstr\adminlte\widgets\Menu::widget(
                [
                    'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget' => 'treeview'],
                    'items' => [
                        //['label' => 'Настройки', 'header' => true],
                        ['label' => 'Базовые', 'iconType' => 'fas', 'icon' => 'cog', 'url' => ['setting/base']],
                        ['label' => 'Прокси', 'iconType' => 'fas', 'icon' => 'server', 'url' => ['setting/proxy']],
                    ],
                ]
            ) ?>
        </nav>
    </div>
</aside>

<?php

/** @var yii\web\View $this */
/** @var string $directoryAsset */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<nav class="main-header navbar navbar-expand navbar-dark navbar-navy">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/blacklist/index']) ?>" class="nav-link">Черный список</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/user/index']) ?>" class="nav-link">Пользователи</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/searx/index']) ?>" class="nav-link">Searx Инстансы</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['/whoogle/index']) ?>" class="nav-link">Whoogle</a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <span class="d-none d-md-inline"><i class="fas fa-user mr-1"></i></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-footer"><?= Yii::$app->user->identity->username ?></li>
                <li class="user-footer">
                    <?= Html::a(
                        'Sign out',
                        ['site/logout'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat float-right']
                    ) ?>
                </li>
            </ul>
        </li>
    </ul>
</nav>

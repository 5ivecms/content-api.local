<?php

$this->title = 'Базовые настройки';

?>

<div class="row">
    <?= $this->render('base/_cache', ['settings' => $cacheSettings]);?>
</div>
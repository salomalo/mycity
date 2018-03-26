<?php
/**
 * @var $this \yii\web\View
 */
?>
<div class="counter">
    <div class="live_internet">
        <?= $this->render('live_internet'); ?>
    </div>
    <div class="bigmir">
        <?= $this->render('bigmir'); ?>
    </div>
    <div>
        <?= $this->render('yandex'); ?>
    </div>
    <div>
        <?= $this->render('google'); ?>
    </div>
</div>
<?php
/**
 * @var $this yii\base\View
 * @var $countViews integer
 */
?>
<div class="small-box bg-purple">
    <div class="inner">
        <h3><?= $countViews ?></h3>

        <p><?= Yii::t('app', 'Views') ?></p>
    </div>
    <div class="icon">
        <i class="fa fa-shopping-cart"></i>
    </div>
    <a href="#" class="small-box-footer" style="cursor:default;" onclick="return false">
        <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>

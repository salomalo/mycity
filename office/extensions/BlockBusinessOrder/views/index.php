<?php
/**
 * @var $this yii\base\View
 * @var $countOrder integer
 */
use yii\helpers\Url;

?>

<div class="small-box btn-info">
    <div class="inner">
        <h3><?= $countOrder ?></h3>

        <p><?= Yii::t('app', 'Orders_in_one_business') ?></p>
    </div>
    <div class="icon">
        <i class="ion ion-person-add"></i>
    </div>
    <a href="<?= Url::to(['/order/index'])?>" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>
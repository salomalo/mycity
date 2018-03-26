<?php
/**
 * @var $this yii\base\View
 * @var $countQuestion integer
 * @var $businessId integer
 */
use yii\helpers\Url;

?>

<div class="small-box btn-primary">
    <div class="inner">
        <h3><?= $countQuestion ?></h3>

        <p><?= Yii::t('app', 'Question')  ?></p>
    </div>
    <div class="icon">
        <i class="ion ion-stats-bars"></i>
    </div>
    <a href="<?= Url::to(['/conversation/index', 'business_id' => $businessId]) ?>" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>

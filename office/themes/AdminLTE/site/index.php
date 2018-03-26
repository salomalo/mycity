<?php
/**
 * @var yii\web\View $this
 * @var integer $myOrders
 * @var integer $orderInBusiness
 * @var integer $ads
 */

use office\extensions\BlockBusinessQuestion\BlockBusinessQuestion;
use office\extensions\BlockBusinessView\BlockBusinessView;
use office\extensions\BlockComment\BlockComment;
use yii\helpers\Url;

$this->title = Yii::t('app', 'My_office');
?>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?= $myOrders ?></h3>

                <p><?= Yii::t('app', 'My_purchases') ?></p>
            </div>
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="<?= Url::to(['/my-order/index']) ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box btn-primary">
            <div class="inner">
                <h3><?= $orderInBusiness ?></h3>

                <p><?= Yii::t('app', 'Orders_in_business') ?></p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?= Url::to(['/order/index']) ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box btn-info">
            <div class="inner">
                <h3><?= $ads ?></h3>

                <p><?= Yii::t('app', 'Ads') ?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?= Url::to(['/ads/index']) ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box btn-success">
            <div class="inner">
                <h3><?= $ads ?></h3>

                <p><?= Yii::t('app', 'Ads_active') ?></p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

<div class="row create-btn-row">
    <div class="col-lg-3 col-xs-6">
        <a href="<?= Url::to(['/ads/create']) ?>">
            <button type="button" class="btn btn-block bg-purple btn-lg"><?= Yii::t('ads', 'Create_ads') ?></button>
        </a>
    </div>

    <div class="col-lg-3 col-xs-6">
        <a href="<?= Url::to(['/business/create']) ?>">
            <button type="button" class="btn btn-block btn-primary btn-lg"><?= Yii::t('business', 'Create_Business') ?></button>
        </a>
    </div>

    <div class="col-lg-3 col-xs-6">
        <a href="<?= Url::to(['/afisha/create']) ?>">
            <button type="button" class="btn btn-block btn-info btn-lg"><?= Yii::t('afisha', 'Afisha_create') ?></button>
        </a>
    </div>

    <div class="col-lg-3 col-xs-6">
        <a href="<?= Url::to(['/work-vacantion/create']) ?>">
            <button type="button" class="btn btn-block btn-success btn-lg"><?= Yii::t('app', 'Vacantion_create') ?></button>
        </a>
    </div>
</div>
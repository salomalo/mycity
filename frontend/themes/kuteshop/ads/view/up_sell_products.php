<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 * @var $business \common\models\Business
 */
use common\models\Ads;

/** @var Ads[] $ads */
$adsModels = $business->getAds()->orderBy(['views' => SORT_DESC])->where(['not', '_id', ['$eq' => $model->_id]])->limit(6)->all();
?>
<!-- block-Upsell Products -->
<div class="block-upsell ">
    <div class="block-title">
        <strong class="title">Вам также могут понравиться</strong>
    </div>
    <div class="block-content ">
        <ol class="product-items owl-carousel "
            data-nav="true"
            data-dots="false"
            data-margin="30"
            data-responsive='{"0":{"items":1},"480":{"items":2},"600":{"items":3},"992":{"items":3}}'>

            <?php foreach ($adsModels as $ads) : ?>
                <?= $this->render('_short_ads', ['model' => $ads]) ?>
            <?php endforeach; ?>

        </ol>
    </div>
</div><!-- block-Upsell Products -->

<?php
/**@var $this \yii\web\View */
/**@var $business \common\models\Business */
/**@var $idsModelInBasket array  */
//var_dump($idsModelInBasket);
$test = \common\models\Ads::findOne('589465b1279871c1048b4567');

$adsModels = $business->getAds()
    ->where(['isShowOnBusiness' => '1'])
    ->andWhere(['not in','_id',$idsModelInBasket])
    ->orderBy(['isShowOnBusiness' => SORT_DESC])
    ->limit(3)
    ->all();

?>

<div class="listing-detail-section">
    <h2 class="page-header" style="margin-top: 40px;">
        Похожие объявления
    </h2>
    <div class="b-product-line b-product-line_size_wide js-gallery-container">
        <?php foreach ($adsModels as $ads) : ?>
            <?= $this->render('_short_ads', ['model' => $ads]) ?>
        <?php endforeach; ?>
    </div>
</div>

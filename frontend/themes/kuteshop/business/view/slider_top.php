<?php
/** @var $business Business */


use common\models\Ads;
use common\models\Business;
use yii\helpers\Url;

$aliasBusiness = "{$business->id}-{$business->url}";
/** @var Ads $newProduct */
$newProduct = Ads::find()
    ->where(['idBusiness' => $business->id])
    ->limit(1)
    ->one();

/** @var Ads[] $adsList */
$adsList = [];

if ($newProduct) {
    $topRatingProducts = Ads::find()
        ->where(['idBusiness' => $business->id])
        ->andWhere(['!=', '_id', $newProduct->_id])
        ->orderBy(['views' => SORT_DESC])
        ->limit(2)
        ->all();

    $adsList[] = $newProduct;
    foreach ($topRatingProducts as $ad){
        $adsList[] = $ad;
    }
}

?>
<?php if (!empty($adsList)) : ?>
    <!-- block slide top -->
    <div class="block-sidebar block-banner-sidebar">
        <div class="owl-carousel"
             data-nav="false"
             data-dots="true"
             data-margin="0"
             data-items='1'
             data-autoplayTimeout="700"
             data-autoplay="true"
             data-loop="true">
            <?php foreach ($adsList as $key => $model) : ?>
                <div class="item item<?= $key ?>">
                    <a href="<?= Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$model->_id}-{$model->url}"]) ?>">
                        <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" alt="<?= $model->title ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div><!-- block slide top -->
<?php endif; ?>
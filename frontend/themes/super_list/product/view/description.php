<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
use yii\helpers\Html;

?>

<div class="listing-detail-section" id="listing-detail-section-description">
    <h2 class="page-header"><?= Yii::t('business', 'Detailed_Description') ?></h2>

    <div class="listing-detail-description-wrapper">
        <?php if($model->image):?>

        <?php
//        echo Html::a(
            echo   Html::img(
                \Yii::$app->files->getUrl($model, 'image'),
                [
                    'class' => 'big',
                    'width' => 170,
                    'alt' => $model->title
                ]
            )
//            ,
//            Yii::$app->files->getUrl($model, 'image'),
//            [
//                'class' => 'fancybox',
//                'data-fancybox-group' => 'gallery',
//                'title' => ''
//            ]
//        );
        ?>

        <?php endif;?>
        <div class="prices">
            <div class="row">
                <?php if((empty($minPrice))&&(empty($maxPrice))): ?>
                    <div class="price"><?= Yii::t('product', 'No_price_offers')?></div>
                <?php else: ?>
                    <div class="price"><?= Yii::t('product', 'Price_range')?> : <?= $minPrice; ?> - <?= $maxPrice; ?> грн.</div>
                <?php endif; ?>
                <div class="predl-label"><?= Yii::t('product', 'Offers')?> <?= $model->getCountAds($model->_id); ?></div>
            </div>
        </div>

        <p><?= $model->description ?></p>
    </div>
</div>
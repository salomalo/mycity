<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\BasketButton\BasketButton;
use frontend\extensions\MyStarRating\MyStarRating;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;
?>

<div class="detail-banner detail-banner-simple" style="margin-top: 7px;">
    <div class="detail-banner-shadow"></div>

    <div class="container">
        <div class="detail-banner-left">

            <div class="detail-banner-info">
                <div class="detail-label">
                    <?= $model->category ? Html::a($model->category->title) : ''?>
                </div>
            </div>

            <div class="detail-banner-after">
                <h1 class="detail-title"><?= $model->title ?></h1>
                
                <div class="inventor-reviews-rating" style="z-index:1000">
                    <?= MyStarRating::widget(['id' => (string)$model->_id, 'rating' => $model->rating, 'url' => ['/ads/rating-change']]) ?>
                </div>

                <?= ShareButton::widget(['title' => $model->title]) ?>
                <?= AddFavourite::widget(['id' => (string)$model->_id, 'type' => File::TYPE_ADS]) ?>
            </div>

        </div>

        <?= BasketButton::widget(['model_id' => $model->_id]) ?>
    </div>
</div>
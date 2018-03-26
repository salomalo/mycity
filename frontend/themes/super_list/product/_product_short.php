<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Product
 */

use common\extensions\ViewCounter\ProductViewCounter;
use common\models\File;
use frontend\extensions\MyStarRating\MyStarRating;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

$alias = "{$model->_id}-{$model->url}";
$countAds = $model->getCountAds($model->_id);
$image = $model->image ? Html::img(Yii::$app->files->getUrl($model, 'image', 100), ['alt' => $model->title, 'class' => 'action-img']) : null;
?>

<div class="listing-container">
    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a($image, ['/product/view', 'alias' => $alias]) ?>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['/product/view', 'alias' => $alias], ['class' => 'my-color-link']) ?>
            </h2>
        </div>

        <div class="listing-row-properties">
            <dl>
                <dt><?= Yii::t('ads', 'Comments') ?></dt>
                <dd><?= $model->getComments((string)$model->_id) ?></dd>

                <?php if ($model->category) : ?>
                    <dt><?= Yii::t('product', 'Products_Categories') ?></dt>
                    <dd><?= Html::a($model->category->title, ['product/index', 'pid' => $model->category->url]) ?></dd>
                <?php endif; ?>
            </dl>

            <?php if ($countAds > 0): ?>
                <div class="price">
                    <div class="money"><?= Yii::t('product', 'Price') ?></div>
                    <div class="predl"><?= Yii::t('product', 'Offers') ?> <?= $countAds; ?></div>
                </div>
            <?php endif; ?>

            <dt><?= Yii::t('business', 'Rating') ?></dt>
            <dd>
                <span class="review-rating" data-fontawesome="" data-staron="fa fa-star" data-starhalf="fa fa-star-half-o" data-staroff="fa fa-star-o" title="good">
                    <?= MyStarRating::widget(['id' => (string)$model->_id, 'rating' => $model->rating, 'readOnly' => true]) ?>
                </span>
            </dd>

            <dt><?= Yii::t('business', 'views') ?></dt>
            <dd><?= (int)ProductViewCounter::widget(['item' => $model, 'count' => false]) ?></dd>
        </div>
    </div>
</div>
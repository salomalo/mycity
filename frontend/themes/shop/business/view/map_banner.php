<?php
/**
 * @var $model \common\models\Business
 * @var $this \yii\web\View
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\ThemeButtons\ShareButton;
use frontend\extensions\MyStarRating\MyStarRating;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
?>

<div class="detail-banner" style="margin-top: 7px;">

    <div id="banner-street-view"
         data-latitude="<?= $model->address[0]->lat ?>"
         data-longitude="<?= $model->address[0]->lon ?>"
         data-zoom="0.66"
         data-heading="41.015"
         data-pitch="12.499"
    >
    </div>

    <div class="detail-banner-shadow"></div>

    <div class="container">
        <div class="detail-banner-left">
            <div class="detail-banner-info">
                <?php foreach ($model->businessCategories as $category) : ?>
                    <div class="detail-label">
                        <?= Html::a($category->title, ['/business/index', 'pid' => $category->url]) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <h1 class="detail-title"><?= $model->title ?></h1>

            <div class="detail-banner-address">
                <i class="fa fa-map-marker"></i><?= $model->address[0]->address ?>
            </div>

            <div class="detail-banner-after" style="z-index:1000">
                <?= MyStarRating::widget(['id' => $model->id, 'rating' => $model->rating, 'url' => ['/business/rating-change']]) ?>
            </div>

            <?= ShareButton::widget(['title' => $model->title]) ?>
            <?= AddFavourite::widget(['id' => $model->id, 'type' => File::TYPE_BUSINESS]) ?>
        </div>
    </div>
</div>
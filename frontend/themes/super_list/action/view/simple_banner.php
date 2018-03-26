<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\MyStarRating\MyStarRating;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Url;
?>

<div class="detail-banner detail-banner-simple" style="margin-top: 7px;">
    <div class="detail-banner-shadow"></div>

    <div class="container">
        <div class="detail-banner-left">
            <?php if (isset($model->company)) : ?>
            <div class="detail-banner-info">
                <div class="detail-label">
                    <a><?= isset($model->company) ? $model->company->title : ''?></a>
                </div>
            </div>
            <?php endif; ?>

            <h1 class="detail-title"><?= $model->title ?></h1>

            <?= ShareButton::widget(['title' => $model->title]) ?>
            <?= AddFavourite::widget(['id' => $model->id, 'type' => File::TYPE_ACTION]) ?>

            <?php if (isset($model->company)): ?>
                <div class="detail-banner-after">
                    <div class="inventor-reviews-rating" style="z-index:1000">
                        <?= MyStarRating::widget(['id' => $model->company->id, 'rating' => $model->company->rating]) ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
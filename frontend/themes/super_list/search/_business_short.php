<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\extensions\ViewCounter\BusinessViewCounter;
use common\models\File;
use frontend\extensions\MyStarRating\MyStarRating;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

$alias = "{$model->id}-{$model->url}";
mb_internal_encoding('UTF-8');
?>

<div class="listing-container">
    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a(
                Html::img(Yii::$app->files->getUrl($model, 'image', 200), ['alt' => $model->title]),
                ['/business/view', 'alias' => $alias],
                ['class' => 'listing-row-image-link']
            ) ?>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['/business/view', 'alias' => $alias], ['class' => 'title']) ?>
            </h2>
            <div class="listing-row-content">
                <?php if ($model->isChecked) : ?>
                    <p>
                        <?= mb_substr(strip_tags($model->description), 0, 150), ($model->description == '' ? '' : '...') ?>
                    </p>
                <?php endif; ?>
                <p>
                    <?= Html::a(Yii::t('business', 'Read more'), ['/business/view', 'alias' => $alias], ['class' => 'title']) ?>
                </p>
            </div>
        </div>

        <div class="listing-row-properties">
            <dl>
                <?php if(!empty($model->address[0])):?>
                    <dt><?= Yii::t('business', 'Address')?></dt>
                    <dd><span><?= $model->address[0]['address'] ?></dd>
                <?php endif;?>

                <dt><?= Yii::t('business', 'Rating') ?></dt>
                <dd class="">
                    <span class="review-rating" data-fontawesome="" data-staron="fa fa-star" data-starhalf="fa fa-star-half-o" data-staroff="fa fa-star-o" title="good">
                        <?= MyStarRating::widget(['id' => $model->id, 'rating' => $model->rating, 'readOnly' => true]) ?>
                    </span>
                </dd>

                <dt><?= Yii::t('business', 'views') ?></dt>
                <dd><?= BusinessViewCounter::widget(['item' => $model->id, 'categories' => $model->idCategories])?></dd>
            </dl>
        </div>
    </div>
</div>
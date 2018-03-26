<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $backgroundDisplay bool
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\MyStarRating\MyStarRating;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$background = $model->background_image ? Yii::$app->files->getUrl($model, 'background_image') : null;
?>

<?php if ($background && isset($backgroundDisplay)  && $backgroundDisplay && ((strtotime($model->due_date) > (time() - 3600 * 24 * 7)))): ?>
    <div class="detail-banner" style="margin-top: 7px; background: url('<?= $background ?>') 50% 100% no-repeat; background-size: cover; " >
<?php else: ?>
    <div class="detail-banner detail-banner-simple" style="margin-top: 7px;">
<?php endif; ?>

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

            <h1 class="detail-title" itemprop="name"><?= $model->title ?></h1>

            <div class="detail-banner-address">
                <?php if (isset($model->address[0])) : ?>
                    <i class="fa fa-map-marker"></i><?= $model->address[0]->address ?>
                <?php endif; ?>
            </div>

            <div class="detail-banner-after">
                <div class="inventor-reviews-rating" style="z-index:1000">
                    <?= MyStarRating::widget(['id' => $model->id, 'rating' => $model->rating, 'url' => ['/business/rating-change']]) ?>
                </div>

                <?= ShareButton::widget(['title' => $model->title]) ?>
                <?= AddFavourite::widget(['id' => $model->id, 'type' => File::TYPE_BUSINESS]) ?>
            </div>

        </div>
    </div>
</div>
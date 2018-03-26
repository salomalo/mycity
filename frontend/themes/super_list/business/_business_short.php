<?php
/**
 * @var $this \yii\web\View
 * @var $view integer
 * @var $model \common\models\Business
 */

use common\extensions\ViewCounter\BusinessViewCounter;
use frontend\extensions\MyStarRating\MyStarRating;
use yii\helpers\Html;
use yii\helpers\Url;

$alias = "{$model->id}-{$model->url}";
mb_internal_encoding('UTF-8');

$image = Html::img(Yii::$app->files->getUrl($model, 'image', 600), ['alt' => $model->title, 'class' => 'action-img']);

$city = Yii::$app->request->city;
$url = Url::to(['/business/view', 'alias' => $alias]);
$front = Yii::$app->params['appFrontend'];
if ($model->city && (($city && ($model->idCity !== $city->id)) || !$city)) {
    $url = "http://{$model->city->subdomain}.{$front}{$url}";
}
?>

<div class="listing-container">
    <div class="listing-row">
        <div class="row">
            <div class="col-md-4">
                <div class="short-image">
                    <?= Html::a($image, $url) ?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 10px;">
                                <h2 class="listing-row-title">
                                    <?= Html::a($model->title, $url, ['class' => 'title']) ?>
                                </h2>
                            </div>
                        </div>

                        <?php if (!empty($model->address[0])) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p>
                                        <strong><?= Yii::t('business', 'Address') ?></strong>
                                        <?= $model->address[0]->address ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="listing-row-content">
                                    <?php if ($model->isChecked) : ?>
                                        <p><?= mb_substr(strip_tags($model->description), 0, 150), ($model->description == '' ? '' : '...') ?></p>
                                    <?php endif; ?>
                                    <p><?= Html::a(Yii::t('business', 'Read more'), $url, ['class' => 'title']) ?></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="listing-row-properties">
                                    <dl>
                                        <dt><?= Yii::t('business', 'Rating') ?></dt>
                                        <dd>
                                        <span class="review-rating" data-fontawesome="" data-staron="fa fa-star" data-starhalf="fa fa-star-half-o" data-staroff="fa fa-star-o" title="good">
                                            <?= MyStarRating::widget(['id' => $model->id, 'rating' => $model->rating, 'readOnly' => true]) ?>
                                        </span>
                                        </dd>

                                        <dt><?= Yii::t('business', 'views') ?></dt>
                                        <dd><?= $view ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
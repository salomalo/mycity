<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\extensions\ViewCounter\BusinessViewCounter;
use common\models\Business;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use frontend\models\BusinessContact;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$image = Yii::$app->files->getUrl($model, 'image', 200);

$vk_url = str_replace(['http://', 'https://'], '', $model->urlVK);
$fb_url = str_replace(['http://', 'https://'], '', $model->urlFB);
$tw_url = str_replace(['http://', 'https://'], '', $model->urlTwitter);

$user = Yii::$app->user->identity;
?>

<div class="col-sm-4 col-lg-3">
    <div id="secondary" class="secondary sidebar">

        <div id="listing_author-2" class="widget widget_listing_author" style="margin-top: 40px;">
            <div class="widget-inner">
                <div class="listing-author">
                    <?= Html::a(Html::img($image, ['itemprop' => 'image']), null, ['class' => 'listing-author-image', 'data' => ['background-image' => $image]]) ?>
                    <meta itemprop="image" content="<?= $image ?>">
                    <div class="listing-author-social">
                        <?php if ($vk_url or $fb_url or $tw_url) : ?>
                            <?php if ($vk_url) : ?>
                                <span class="value">
                                    <noindex><?= Html::a(Html::img('/img/icons/vk.png'), "http://$vk_url") ?></noindex>
                                </span>
                            <?php endif; ?>

                            <?php if ($fb_url) : ?>
                                <span class="value">
                                    <noindex><?= Html::a(Html::img('/img/icons/fb.png'), "http://$fb_url") ?></noindex>
                                </span>
                            <?php endif; ?>

                            <?php if ($tw_url) : ?>
                                <span class="value">
                                    <noindex><?= Html::a(Html::img('/img/icons/tw.png'), "http://$tw_url") ?></noindex>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="listing_details-2" class="widget widget_listing_details">
            <div class="widget-inner">

                <div class="inventor-statistics-total-post-views">
                    <i class="fa fa-eye"></i>
                    <strong><?= BusinessViewCounter::widget(['item' => $model->id, 'categories' => $model->idCategories]) ?></strong>
                </div>

                <?php if ($model->rating) : ?>
                    <div class="inventor-reviews-total-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                        <i class="fa fa-star"></i>
                        <strong itemprop="ratingValue"><?= $model->rating ?></strong>
                        <meta itemprop="worstRating" content="1"/>
                        <meta itemprop="bestRating" content="5"/>
                        <meta itemprop="ratingCount" content="<?= $model->quantity_rating ?>"/>
                        <meta itemprop="reviewCount" content="<?= $model->commentsCount ?>"/>
                    </div>
                <?php endif; ?>

                <div><?= BusinessFormatTime::widget(['id' => $model->id]) ?></div>

                <?php if ($model->idUser === 1) : ?>
                    <?php if ($user) : ?>
                        <?php $userOwn = Business::find()->where(['idUser' => Yii::$app->user->id])->count(); ?>

                        <?php if ($userOwn > 0) : ?>
                            <?php $myBusiness = ['url' => null, 'options' => ['id' => 'owner-support', 'data' => ['url' => Url::to(['/site/owner-support'])]]]; ?>
                        <?php else : ?>
                            <?php $myBusiness = ['url' => Yii::$app->urlManagerOffice->createUrl(['business/pay', 'id' => $model->id])]; ?>
                        <?php endif; ?>

                    <?php else : ?>
                        <?php $myBusiness = ['url' => ['/'], 'options' => ['onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;', 'rel' => 'nofollow']]; ?>
                    <?php endif; ?>

                    <?php $myBusiness['options']['class'] = 'btn btn-primary' ?>
                    <?php $myBusiness['title'] = '<span class="glyphicon glyphicon-user"></span> ' . Yii::t('business', 'I am owner') ?>

                    <div>
                        <?= Html::a($myBusiness['title'], $myBusiness['url'], $myBusiness['options']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
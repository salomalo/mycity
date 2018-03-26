<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */

use common\extensions\ViewCounter\AdsViewCounter;
use common\models\Profile;
use common\models\UserPaymentType;
use common\models\UserPaymentTypeBusiness;
use frontend\extensions\AdBlock;
use yii\helpers\Html;
use yii\helpers\Url;

$phone = null;
$userName = null;

if (isset($model->user->username)){
    $userName = $model->user->username;
} elseif (isset($model->business->user->username)){
    $userName = $model->business->user->username;
}

if (isset($model->business->phone) && $model->business->phone != ''){
    $phone = $model->business->phone;
} else{
    $sellerId = $model->idUser;
    if (isset($model->idUser) && $model->idUser){
        $profile = Profile::findOne($model->idUser);
        $phone = isset($profile->phone) && $profile->phone != '' ? $profile->phone : null;
    }
}

$paymentType = UserPaymentType::find()->where(['user_id' => $model->idUser])->all();
?>

<div class="col-sm-4 col-lg-3">
    <div id="secondary" class="secondary sidebar">
        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

        <div id="listing_author-2" class="widget widget_listing_author">
            <div class="widget-inner">
                <div class="listing-author">
                    <?php if ($model->business) : ?>
                        <div>
                            <?= Html::a($model->business->title, ['/business/view', 'alias' => "{$model->idBusiness}-{$model->business->url}"]) ?>
                        </div>

                        <?php $imageUrl = Yii::$app->files->getUrl($model->business, 'image', 200); ?>
                        <?= Html::a(Html::img($imageUrl), null, [
                            'class' => 'listing-author-image',
                            'data' => ['background-image' => $imageUrl]
                        ]) ?>

                    <?php elseif ($model->user && $model->user->profile) : ?>
                        <div><?= $model->user->getExistName() ?></div>

                        <?= Html::a(Html::img($model->user->profile->getAvatarUrl()), null, [
                            'class' => 'listing-author-image',
                            'data' => ['background-image' => $model->user->profile->getAvatarUrl()]
                        ]) ?>

                        <hr>

                        <?php if ($model->user->profile->phone) : ?>
                            <div>Телефон: <?= $model->user->profile->phone ?></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <?= Html::a(Html::img('/img/avatar.png'), null, [
                            'class' => 'listing-author-image',
                            'data' => ['background-image' => '/img/avatar.png']
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="listing_details-2" class="widget widget_listing_details">
            <div class="widget-inner">

                <?php if (!Yii::$app->user->isGuest) : ?>
                    <?php if (isset($userName)) : ?>
                        <div class="inventor-statistics-total-post-views">
                            <i class="fa fa-user"></i>
                            <strong><?= $userName ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if ($phone) : ?>
                        <div class="inventor-statistics-total-post-views">
                            <i class="fa fa-phone"></i>
                            <strong><?= $phone ?></strong>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="inventor-statistics-total-post-views">
                        <i class="fa fa-user"></i>
                        <strong>
                            <?= Html::a('Показать контактные данные продавца', ['/'], [
                                'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;',
                                'rel' =>'nofollow',
                            ]) ?>
                        </strong>
                    </div>
                <?php endif; ?>

                <div class="inventor-statistics-total-post-views">
                    <i class="fa fa-eye"></i>
                    <strong><?= (int)AdsViewCounter::widget(['item' => $model, 'count' => false]) ?></strong> <?= Yii::t('business', 'view_1') ?>
                </div>

            </div>
        </div>

        <?php if (count($paymentType) > 0) : ?>
            <div id="listing_details-2" class="widget widget_listing_details">
                <div class="widget-inner">
                    <div class="inventor-statistics-total-post-views">
                        Способы оплаты:
                        <?php
                        foreach ($paymentType as $temp) {
                            echo '<div class="ads-payment-type"><strong>' . $temp->paymentType->title . '</strong></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
    </div>
</div>
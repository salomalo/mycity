<?php
/** @var \common\models\Business $model */

use common\models\UserPaymentType;
use frontend\extensions\StanzaLatestBlog\StanzaLatestBlog;
use yii\helpers\Url;

$businessAddress = null;
if ($model->address) {
    foreach ($model->address as $address){
        if (isset($address->street)){
            $businessAddress = $address->street . ', ' . $address->city . ' ' . $address->country;
        } else {
            $businessAddress = $address->address;
        }
        break;
    }
}

/** @var UserPaymentType[] $paymentType */
$paymentType = $model->userPaymentType;
?>
<footer class="footer style1">
    <div class="stripe-1 stripe_1 foot_widgets">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="widget_container">
                        <div class="widget_text clearfix">
                            <p>
                                <a href="<?= Url::to(['/business/view', 'alias' => "{$model->id}-{$model->url}"]) ?>">
                                    <img src="<?= Yii::$app->files->getUrl($model, 'image', 200); ?>" alt="" style="width: 208px; height: 66px; object-fit: contain;"/>
                                </a>
                            </p>
                            <p class="marginBottomNone"><?= $model->title?></p>
                        </div><!-- ( WIDGET TEXT END ) -->
                    </div>
                </div>

                <?= StanzaLatestBlog::widget(['businessModel' => $model, 'limit' => 1, 'template' => 'recent_blog_posts']) ?>
            </div>
        </div>
    </div><!-- ( FOOTER WIDGETS END ) -->
</footer><!-- ( FOOTER END ) -->
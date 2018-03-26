<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 * @var $business \common\models\Business
 * @var $alias string
 */
use common\models\File;
use frontend\extensions\BasketButton\BasketButton;
use frontend\extensions\ThemeButtons\AddFavourite;
use yii\helpers\Html;
use yii\helpers\Url;

$aliasBusiness = "{$business->id}-{$business->url}";
$url = Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$model->_id}-{$model->url}"]);
?>

<div class="productBox">
    <div class="productImage hoverStyle" style="height: 332px;">
        <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" width="263" height="332" alt=""
             style="width: 100%;height: 100%;object-fit: contain;">
        <?php if ($model->discount) : ?>
            <span class="sale">Скидка!</span>
        <?php endif; ?>
        <div class="hoverBox">
            <div class="hoverIcons">
                <a href="<?= $url ?>" class="eye hovicon"><i
                        class="fa fa-eye"></i></a>
                <?= AddFavourite::widget([
                    'id' => $model->_id,
                    'type' => File::TYPE_ADS,
                    'template' => 'favorite_stanza',
                ]) ?>
            </div><!-- ( HOVER ICONS END ) -->

            <a href="javascript:void(0);" class="cartBTN2" style="padding:0;">
                <?= BasketButton::widget(['model_id' => $model->_id, 'template' => 'stanza', 'alias' => $alias]) ?>
            </a>
        </div><!-- ( HOVER BOX END ) -->
    </div><!-- ( PRODUCT IMAGE END ) -->
    <div class="productDesc">
            <span class="product-title" style="overflow: hidden;">
                <?= Html::a($model->title,  $url, ['style' => 'white-space: nowrap;']) ?>
            </span>
        <?php if (isset($model->category->title)): ?>
            <p style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                <?= $model->category->title ?>
            </p>
        <?php endif; ?>
        <div class="stars">
            <span class="starsimgRating"></span>
        </div><!-- ( STARS END ) -->
        <strong class="productPrice"><?= $model->price ?> грн.</strong>
    </div><!-- ( PRODUCT DESCRIPTION END ) -->
</div><!-- ( PRODUCT BOX END ) -->

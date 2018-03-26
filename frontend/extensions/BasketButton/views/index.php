<?php
use yii\helpers\Html;
/**
 *@var bool $isInBasket
 *@var \common\models\Ads $model number items in basket
 */
?>

<div class="detail-banner-right affix-top basket-top">
    <div class="detail-banner-price reduced-price">
        <?php if ($model->discount) : ?>
            <span class="detail-banner-price-label">Скидочная цена </span>
            <span class="detail-banner-price-value"><?= $model->price * (1 - $model->discount / 100) ?> грн.</span>
        <?php else: ?>
            <span class="detail-banner-price-label"><?= Yii::t('ads', 'Price') ?></span>
            <span class="detail-banner-price-value"><?= $model->price ?> грн.</span>
        <?php endif; ?>

        <!--                --><?php //if (!Yii::$app->user->isGuest) : ?>
        <div class="inventor-shop-wrapper">

            <?= Html::beginForm(['shopping-cart/add-shopping-cart'], 'post', [
                'id' => 'add-to-cart',
                'product-id' => $model->_id,
                'style' => $isInBasket ? 'background-color: rgb(141, 198, 63);' : ''
            ]) ?>
            <?= Html::hiddenInput('id', $model->_id) ?>
            <?= Html::button('Купить') ?>
            <?= Html::endForm() ?>

        </div>
        <!--                --><?php //endif; ?>
    </div>
</div>

<?php
/**
 * @var \common\models\Ads $model number items in basket
 * @var $isInBasket bool
 */
use yii\helpers\Html;
use yii\helpers\Url;
$textButton = $isInBasket ? 'В корзине' : 'Купить';
?>

<?= Html::beginForm(['shopping-cart/add-shopping-cart'], 'post', [
    'id' => 'add-to-cart-' . $model->_id,
    'product-id' => $model->_id,
]) ?>
<?= Html::hiddenInput('id', $model->_id) ?>
<?= Html::input('submit',null, $textButton, ['class' => 'b-product-gallery__btn-buy js-btn-buy b-button_size_small h-mt-10 js-product-ad-conv-action js-product-buy-button qa-buy-button b-button b-button_theme_dark-orange', 'style' => 'border-color: #d86c1b !important;color: #fff; !important;background: #ff911b !important; border-radius: 3px;min-height: 25px !important']) ?>
<?= Html::endForm() ?>
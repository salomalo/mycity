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
<?= Html::input('submit',null, $textButton, [
    'style' => 'background:none;width:100%;padding:15px;']) ?>
<?= Html::endForm() ?>

<?php
use yii\helpers\Html;
/**
 * @var bool $isInBasket
 * @var \common\models\Ads $model number items in basket
 * @var $alias string
 */

$textButton = $isInBasket ? 'В корзине' : 'Купить';
?>


<?= Html::beginForm(['shopping-cart/add-shopping-cart'], 'post', [
    'id' => 'add-to-cart-' . $model->_id . '-' . $alias,
    'product-id' => $model->_id,
]) ?>
<?= Html::hiddenInput('id', $model->_id) ?>
<?= Html::input('submit', null, $textButton, ['class' => 'btn btn-cart ']) ?>
<?= Html::endForm() ?>

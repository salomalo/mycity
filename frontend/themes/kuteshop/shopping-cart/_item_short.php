<?php
/**
 * @var $this \yii\web\View
 * @var $count integer
 * @var $model \common\models\Ads
 */
use yii\helpers\Html;
use yii\helpers\Url;
$alias = "{$model->_id}-{$model->url}";
?>

<tr class="cart-list-item">
    <td class="cart-list-item-meta">
        <div class="cart-list-item-name">
            <a href="<?= Url::to(['/ads/view', 'alias' => $alias]) ?>">
                <span><?= $model->title ?></span>
            </a>
        </div>
    </td>
    <td class="cart-list-item-price"><?= $model->price?>  грн.</td>
    <td class="cart-list-item-num">
        <div class="cart-amount">
            <a href="#" class="cart-amount-minus" name="minus">
                <?= Html::img('img/minus.jpg', ['alt' => 'from_rozetka', 'class' => 'cart-amount-minus-icon sprite', 'width' => '20', 'height' => '20']) ?>
            </a>

            <input name="quantity" type="text" class="input-text cart-amount-input-text" value="<?= $count ?>"
                   id="<?= $model->_id ?>" onchange="changeItem(this.id, this.value)"/>
            <a href="#" class="cart-amount-plus" name="plus">
                <?= Html::img('img/plus.jpg', ['alt' => 'from_rozetka', 'class' => 'cart-amount-plus-icon sprite', 'width' => '20', 'height' => '20']) ?>
            </a>
        </div>
    </td>
    <td class="cart-list-item-total-price" id="<?= $model->_id . 'count' ?>"><?= $model->price * $count ?>  грн.</td>
    <td class="cart-list-item-actions">
        <a class="cart-list-item-remove" href="<?= Url::to(['/shopping-cart/delete', 'id' => $model->_id]) ?>" onClick="return window.confirm('Вы уверены?');"><span class="icon icon-office-52"></span></a>
    </td>
</tr>
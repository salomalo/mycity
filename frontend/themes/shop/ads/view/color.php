<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use common\models\AdsColor;
use yii\helpers\Html;

/** @var AdsColor[] $adsColor */
$adsColor = AdsColor::find()
    ->where(['idAds' => $model->_id])
    ->andWhere(['isShowOnBusiness' => AdsColor::ENABLED])
    ->all();

?>

<div class="listing-detail-section" id="listing-detail-section-video">
    <h2 class="page-header">Цвет товара</h2>
    <div class="listing-detail-description-wrapper">
        <div class="row">
            <div style="display: inline-block">
                <?= Html::beginForm(['shopping-cart/test'], 'post', [
                    'id' => 'form-choose-color',
                    'class' => 'form-choose-color'
                ]) ?>
                <?php foreach ($adsColor as $color) : ?>
                    <label>
                        <input type="radio" name="productColor" value="<?= $color->id ?>"/>
                        <img src="<?= Yii::$app->files->getUrl($color, 'image', 100) ?>">
                    </label>
                <?php endforeach; ?>
                <?= Html::hiddenInput('id', $model->_id) ?>
<!--                --><?php
//                echo Html::input('submit', 'buy', 'В корзину', ['id' => 'new-to-cart-btn'])
//                ?>
                <?= Html::endForm() ?>
            </div>
        </div>
        <div class="row">
            <div class="msg-error sku-msg-error hidden" id="msg-error" style="float:left;padding: 4px 10px;margin-top: 5px;background-color: #fff9eb;border: 1px solid #f7dd89;">
                Пожалуйста, выберите Цвет
            </div>
        </div>
    </div>
</div>
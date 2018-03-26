<?php
/**
 * @var $this \yii\web\View
 * @var $checkout_url string
 * @var $data string
 * @var $signature string
 * @var $language string
 */

use yii\helpers\Html;
?>

<?= Html::beginForm($checkout_url, 'POST', ['accept-charset' => 'utf-8']) ?>
<?= Html::input('hidden', 'data', $data) ?>
<?= Html::input('hidden', 'signature', $signature) ?>
<?= Html::input('image', 'btn_text', null, ['src' => "//static.liqpay.com/buttons/p1{$language}.radius.png"]) ?>
<?= Html::endForm() ?>
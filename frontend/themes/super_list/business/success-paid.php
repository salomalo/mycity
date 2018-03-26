<?php
/**
 * @var $this \yii\web\View
 */
if (Yii::$app->session->hasFlash('success')) {
    $this->registerJs("ga('send', 'event', 'payment-form', 'send');", yii\web\View::POS_END);
}
?>
<div style="height: 500px">
    Вы успешно оплатили услугу
</div>


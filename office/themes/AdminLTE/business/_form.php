<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 * @var yii\widgets\ActiveForm $form
 * @var UserPaymentTypeBusiness $userPaymentLink
 */

use common\models\UserPaymentTypeBusiness;
use office\extensions\BusinessFormWidget\BusinessFormWidget;

?>

<?= BusinessFormWidget::widget([
    '_view' => '_address',
    'data' => $address,
    'relModelName' => 'common\models\BusinessAddress',
    'readOnly' => false,
    'mapWidth' => '640px',
    'getDataByAjax' => false,
    'model' => $model
]) ?>
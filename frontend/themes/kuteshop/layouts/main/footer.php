<?php
/** @var \common\models\Business $businessModel */

use common\models\UserPaymentType;
use yii\helpers\Url;

$businessAddress = null;
if ($businessModel->address) {
    foreach ($businessModel->address as $address){
        if (isset($address->street)){
            $businessAddress = $address->street . ', ' . $address->city . ' ' . $address->country;
        } else {
            $businessAddress = $address->address;
        }
        break;
    }
}

/** @var UserPaymentType[] $paymentType */
$paymentType = $businessModel->userPaymentType;
?>
<!-- FOOTER -->
<footer class="site-footer footer-opt-1">

    <div class="container">
        <div class="footer-column">

            <div class="row">
                <div class="col-md-2 col-lg-2 col-xs-6 col">
                    <strong class="logo-footer">
                        <a href="<?= Url::to(['/business/view', 'alias' => "{$businessModel->id}-{$businessModel->url}"]) ?>">
                            <img src="<?= Yii::$app->files->getUrl($businessModel, 'image', 200) ?>" alt="logo" style="max-height:100px;">
                        </a>
                    </strong>
                </div>
                <div class="col-md-2 col-lg-2 col-xs-6 col">
                    <table class="address">
                        <?php if ($businessAddress) : ?>
                            <tr>
                                <td><b>Адрес: </b></td>
                                <td>
                                    <?= $businessAddress ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($businessModel->phone) : ?>
                            <tr>
                                <td><b>Телефон: </b></td>
                                <td><?= $businessModel->phone ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($businessModel->email) : ?>
                            <tr>
                                <td><b>Email:</b></td>
                                <td><?= $businessModel->email ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($businessModel->skype) : ?>
                            <tr>
                                <td><b>Skype:</b></td>
                                <td><?= $businessModel->skype ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="col-md-6 col-lg-6 col-xs-6 col">
                    <?php if (!empty($paymentType)) : ?>
                        <div class="payment-methods" style="border:none;">
                            <div class="block-title">
                                Методы оплаты
                            </div>
                            <div class="block-content">
                                <?php foreach ($paymentType as $payment): ?>
                                    <img alt="<?= $payment->paymentType->title?>" src="<?= Yii::$app->files->getUrl($payment->paymentType, 'image') ?>" style="width:104px;height:46px;">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</footer><!-- end FOOTER -->
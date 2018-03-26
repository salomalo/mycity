<?php
/**
 * @var $description string
 * @var $price string
 * @var $from string
 * @var $to string
 * @var $business_title string
 * @var $business_link string
 * @var $invoice \common\models\Invoice
 * @var $user \common\models\User
 */

use yii\helpers\Url;

$invoice_link = Yii::$app->params['appOffice'] . '/ru/transactions/view?id=' . $invoice->id;
?>

<div class="container">
    <div class="row">
        <div class="col-md-2">
            <a href="<?= Url::to(['/'], true) ?>"><img src="https://citylife.info/img/og_image.jpg" alt="" style="width: 100px;"></a>
        </div>
        <div class="com-md-6 offset-md-2">
            <p>Уважаемый(ая) <?= $user->getExistName() ?>,</p>
            <p>Мы получили Ваш платеж по счету <a href="<?= $invoice_link ?>"> <?= $invoice->id ?></a>, созданному <?= $invoice->created_at ?>.</p>
            <p>Вы приобрели право на управление предприятием <a href="<?= $business_link ?>"> <?= $business_title ?></a> на один месяц на сайте <a href="<?= Url::to(['/'], true) ?>">CityLife</a>.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <table class="table table-striped"  border="1" cellpadding="4" cellspacing="0">
                <thead class="thead-inverse">
                <tr>
                    <th>№</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>С</th>
                    <th>По</th>
                </tr>
                </thead>
                <tr>
                    <th scope="row">1</th>
                    <td><?= $description ?></td>
                    <td><?= $price ?></td>
                    <td><?= $from ?></td>
                    <td><?= $to ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
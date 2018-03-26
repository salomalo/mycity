<?php
/**
 * @var $description string
 * @var $price string
 * @var $from string
 * @var $to string
 * @var $business_title string
 * @var $order_id string
 */

use yii\helpers\Url;
?>

<link rel="stylesheet" href="/css/bootstrap.min.css">

<div class="container">
    <div class="row">
        <div class="col-md-2">
            <a href="<?= Url::to(['/'], true) ?>"><img src="/img/og_image.jpg" alt="" style="width: 100px;"></a>
        </div>
        <div class="com-md-6 offset-md-2">
            Возникла ощибка при приобритении права на управление предприятием <?= $business_title ?> на сайте <a href="<?= Url::to(['/'], true) ?>">CityLife</a>.
            Обратитесь в службу поддержки <?= Yii::$app->params['contactEmail'] ?>, Ваш код заказа: <?= $order_id ?>.
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <table class="table table-striped">
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
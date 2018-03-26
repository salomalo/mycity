<?php
/**
 * @var $this \yii\web\View
 * @var $payments \common\models\UserPaymentType[]
 */

use yii\helpers\Html;

$this->title =  'Настройки';
?>

<div style="margin-bottom: 20px">
    <p>Мы заметили, что у Вас не установлены способы оплаты.</p>
    <p>Выберите наиболее удобные для Вас способы оплаты - это позволит достичь максимальной эффективности при совершении сделки на сайте
        <a href="https://citylife.info/ru">CityLife.Info</a>
    </p>
</div>


<div class="row">
    <div class="col-xs-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Способы оплаты</h3>

                <div class="box-tools">
                    <?=Html::a('Добавить', ['/user-payment-type/create'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>Способ оплаты</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>

                    <?php foreach ($payments as $payment) : ?>
                        <tr>
                            <td><?= $payment->paymentType->title ?></td>
                            <td><?= $payment->description ?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil-square" aria-hidden="true"></i>', ['/user-payment-type/update', 'id' => $payment->id]) ?>
                                <?= Html::a('<i class="fa fa-bitbucket-square" aria-hidden="true"></i>', ['/user-payment-type/delete', 'id' => $payment->id], [
                                    'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'post'],
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

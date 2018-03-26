<?php
/**
 * @var $this \yii\web\View
 * @var $payments \common\models\UserPaymentType[]
 */

use yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>

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
                        <th>Способы оплаты</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>

                    <?php foreach ($payments as $payment) : ?>
                        <tr>
                            <td><?= $payment->paymentType->title ?></td>
                            <td><?= $payment->description ?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil-square fa-2x" aria-hidden="true"></i>', ['/user-payment-type/update', 'id' => $payment->id]) ?>
                                <?= Html::a('<i class="fa fa-bitbucket-square fa-2x" aria-hidden="true"></i>', ['/user-payment-type/delete', 'id' => $payment->id], [
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
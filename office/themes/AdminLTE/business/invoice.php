<?php
/**
 * @var $this \yii\web\View
 * @var $invoices \common\models\Invoice
 * @var common\models\Business $business
 */

use common\models\Invoice;
use common\models\User;
use yii\helpers\Html;

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank', 'style' => 'margin-left: 20px']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>

<?= $this->render('top_block', ['id' => $business->id, 'active' => 'transactions'])?>

<div class="row">
    <div class="col-xs-12">
        <div class="box-header with-border" style="border-top: 3px solid #d2d6de;background-color: #ffffff;">
            <i class="fa fa-bar-chart"></i>

            <h3 class="box-title">Тариф <?= $business::$priceTypes[$business->price_type] ?></h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Список</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Поиск">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>№</th>
                        <th>Пользователь</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Описание</th>
                        <td></td>
                    </tr>

                    <?php foreach ($invoices as $invoice) : ?>
                        <tr>
                            <td><?= $invoice->id ?></td>
                            <td><?= User::findOne($invoice->user_id)->username ?></td>
                            <td><?= $invoice->paid_from ?></td>
                            <td>
                                <?php if ($invoice->paid_status === Invoice::PAID_NO) : ?>
                                    <span class="label label-danger">Не плачено</span>
                                <?php elseif ($invoice->paid_status === Invoice::PAID_YES) : ?>
                                    <span class="label label-success">Оплачено</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $invoice->description ?></td>
                            <td>
                                <?= Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    ['/transactions/view', 'id' => $invoice->id],
                                    ['title' => 'Оплатить', 'aria-label' => 'Просмотр', 'data-pjax' => '0']
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
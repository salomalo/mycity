<?php
/**
 * @var $this yii\web\View
 * @var $searchModelPT common\models\search\PaymentType
 * @var $dataProviderPT yii\data\ActiveDataProvider
 * @var $dataProviderUPT yii\data\ActiveDataProvider
 */

use common\models\PaymentType;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

$this->title = 'Способы оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Базовые способы оплаты</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Способы оплаты пользователей</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="payment-type-index">
                        <p><?= Html::a('Создать тип', ['create'], ['class' => 'btn btn-success']) ?></p>

                        <?= GridView::widget([
                            'dataProvider' => $dataProviderPT,
                            'filterModel' => $searchModelPT,
                            'columns' => [
                                'id',
                                'title',
                                [
                                    'label' => 'Картинка',
                                    'format' => 'html',
                                    'options' => ['width' => '110px'],
                                    'value' => function (PaymentType $model) {
                                        return $model->image ? Html::img(Yii::$app->files->getUrl($model, 'image'), ['width' => '100']) : null;
                                    },
                                ],
                                ['class' => ActionColumn::className()],
                            ],
                        ]); ?>
                    </div>
                </div>

                <div class="tab-pane" id="tab_2">
                    <div class="user-payment-type-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderUPT,
                            'columns' => [
                                'id',
                                'user.username',
                                'paymentType.title',
                                'description',
                            ],
                        ]); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
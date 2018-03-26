<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Ads[]
 * @var $counts array
 * @var $paymentType \common\models\PaymentType[]
 * @var $doOrderModel frontend\models\ShoppingCartForm
 */

use common\models\Lang;
use common\models\Orders;
use common\models\Region;
use frontend\models\ShoppingCartForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use common\models\PaymentType;

/**@var $this \yii\web\View*/
/**@var $business \common\models\Business*/

$total = 0;

$this->registerJsFile('../js/new/bootstrap-select.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('css/new/startuply.css');

$idsModelInBasket = [];
?>
<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumb'],
                        'homeLink' => false,
                        'links' => $breadcrumbs,
                    ]); ?>
                <?php else : ?>
                    <?= Html::ul([['label' => Yii::t('app', 'Home'), 'url' => $main_string]], ['item' => function ($item, $index) {
                        if (!empty($item['url'])) {
                            return Html::tag('li', Html::a($item['label'], [$item['url']], []), ['class' => false]);
                        } else {
                            return Html::tag('li', $item['label'], ['class' => false]);
                        }
                    }, 'class' => 'breadcrumb']); ?>
                <?php endif; ?>

                <div class="col-sm-8 col-lg-12">
                    <div id="primary">
                        <div class="document-title">
                        </div>
                        <div class="cart-checkout">
                            <div class="cart-checkout-navigation">
                                <ul class="cart-checkout-navigation-list" role="tablist">
                                    <li class="active">
                                        <a href="#review-order" role="tab" data-toggle="tab" aria-controls="review-order" aria-expanded="true" class="cart-checkout-navigation-list-item-link" style="pointer-events: none;">
                                            Корзина
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#dellivery" role="tab" data-toggle="tab" aria-controls="dellivery" aria-expanded="true" class="cart-checkout-navigation-list-item-link" style="pointer-events: none;">
                                            Доставка
                                        </a>
                                    </li>
                                    <?php if (!Yii::$app->user->identity) : ?>
                                        <li>
                                            <a href="#fast-register" role="tab" data-toggle="tab" aria-controls="fast-register" aria-expanded="true" class="cart-checkout-navigation-list-item-link" style="pointer-events: none;">
                                                <?= Yii::t('shopping-cart', 'Quick_registration') ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="container">
                            <?php
                            $model = new Orders();
                            $form = ActiveForm::begin(['enableAjaxValidation' => true,'options' => [
                                'enctype' => 'multipart/form-data',
                                'class' => 'payment-form',
                                'name' => 'doOrderForm'
                            ]]); ?>
                            <div class="cart-checkout-content tab-content">

                                <div id="review-order" role="tabpanel" class="tab-pane fade active in" aria-labelledby="review-order-tab">
                                    <?php if (count($models) > 0): ?>
                                        <h3 class="uppercase">Корзина</h3>
                                        <table class="cart-list" id="table-cart-list">
                                            <thead>
                                            <tr>
                                                <th class="cart-list-item-meta"><?= Yii::t('shopping-cart', 'Product_name') ?></th>
                                                <th class="cart-list-item-price"><?= Yii::t('shopping-cart', 'Unit_price') ?></th>
                                                <th class="cart-list-item-number"><?= Yii::t('shopping-cart', 'Amount') ?></th>
                                                <th class="cart-list-item-total-price"><?= Yii::t('shopping-cart', 'Total_cost') ?></th>
                                                <th class="cart-list-item-actions"></th>
                                            </tr>
                                            </thead>

                                            <?php foreach ($models as $seller) : ?>
                                                <?php foreach ($seller as $model) : ?>

                                                    <?php
                                                    if ($model->discount){
                                                        $model->price = $model->price * (1 - $model->discount / 100);
                                                    }
                                                    $total += $counts[(string)$model->_id] * $model->price;
                                                    ?>

                                                    <tbody>
                                                    <?= $this->render('_item_short', ['model' => $model, 'count' => $counts[(string)$model->_id]]); ?>
                                                    </tbody>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>

                                            <tfoot>
                                            <tr>
                                                <th colspan="5" class="cart-list-item-total">
                                                    <?= Yii::t('shopping-cart', 'Total') ?>:
                                                    <span class="cart-list-item-amount" id="sumAllItem"><?= $total ?> грн.</span>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    <?php else: ?>
                                        <h3 class="uppercase"><?= Yii::t('app', 'Shopping cart empty') ?></h3>
                                    <?php endif; ?>
                                    <?= $this->render('view/same_product', ['business' => $business, 'idsModelInBasket' => $idsModelInBasket]) ?>
                                </div>

                                <div id="dellivery" role="tabpanel" class="tab-pane fade" aria-labelledby="dellivery-tab">
                                    <h3 class="uppercase">Доставка</h3>

                                    <?php if(isset(Yii::$app->user->identity)) : ?>
                                        <?= $form->field($doOrderModel, 'idUser')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false) ?>
                                    <?php endif;?>

                                    <?php
                                    $doOrderModel->idRegion = 1;
                                    echo $form->field($doOrderModel, 'idRegion')->widget(Select2::className(), [
                                        'data' => ArrayHelper::map(Region::find()->select(['id', 'title'])->orderBy('title')->all(), 'id', 'title'),
                                        'options' => [
                                            'placeholder' => 'Выберите область ...',
                                            'id' => 'idRegion',
                                        ],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]); ?>
                                    <?= $form->field($doOrderModel, 'idCity')->widget(DepDrop::className(), [
                                        'data' => [],
                                        'type' => DepDrop::TYPE_SELECT2,
                                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                        'pluginOptions' => [
                                            'placeholder' => 'Выберите город ...',
                                            'initialize' => true,
                                            'depends' => ['idRegion'],
                                            'url' => Url::to(['/site/city']),
                                            'loadingText' => 'Загрузка городов ...',
                                        ],
                                    ]) ?>

                                    <?= $form->field($doOrderModel, 'delivery')->textInput(['maxlength' => 255]) ?>
                                    <?= $form->field($doOrderModel, 'office')->textInput(['maxlength' => 255]) ?>

                                    <?= $form->field($doOrderModel, 'address')->textInput(['maxlength' => 255]) ?>

                                    <?= $form->field($doOrderModel, 'phone')->textInput(['maxlength' => 255]) ?>
                                    <?= $form->field($doOrderModel, 'fio')->textInput(['maxlength' => 255]) ?>

                                    <?php if (Yii::$app->user->identity) : ?>
                                        <?= Html::submitInput(Yii::t('app', 'Оплатить'), [
                                            'class' => 'cart-submit btn btn-solid',
                                            'name' => 'order-button',
                                            'id' => 'order-button',
                                            'disabled' => false,
                                        ]) ?>
                                    <?php endif; ?>

                                </div>

                                <?php if (!Yii::$app->user->identity) : ?>
                                    <div id="fast-register" role="tabpanel" class="tab-pane fade"
                                         aria-labelledby="fast-register-tab">
                                        <h3 class="uppercase"><?= Yii::t('shopping-cart', 'Quick_registration') ?></h3>
                                        <?= $form->field($doOrderModel, 'username', [
                                                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                                'errorOptions' => ['class' => 'help-block-modal'],
                                                'inputOptions' => ['placeholder' => Yii::t('app', 'Your_name_in_the_system'), 'class' => 'modal-input']
                                            ]
                                        )->label(Yii::t('app', 'Only_latin_digit_direct_nyzhnee_podcherkyvanye'))
                                        ?>
                                        <?= $form->field($doOrderModel, 'email', [
                                                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                                'errorOptions' => ['class' => 'help-block-modal'],
                                                'inputOptions' => ['placeholder' => Yii::t('app', 'Your_email'), 'class' => 'modal-input']
                                            ]
                                        )->label(Yii::t('app', 'It_will_be_used_to_login'))
                                        ?>
                                        <?= $form->field($doOrderModel, 'password', [
                                            'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                            'errorOptions' => ['class' => 'help-block-modal'],
                                            'inputOptions' => ['placeholder' => Yii::t('app', 'Your_password'), 'class' => 'modal-input']
                                        ])->passwordInput()->label(Yii::t('app', 'Не менее 6 знаков на латинице'))
                                        ?>

                                        <?= $form->field($doOrderModel, 'password2', [
                                            'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                            'errorOptions' => ['class' => 'help-block-modal'],
                                            'inputOptions' => ['placeholder' => Yii::t('app', 'Your_password'), 'class' => 'modal-input']
                                        ])->passwordInput()->label(Yii::t('app', 'Не менее 6 знаков на латинице'))
                                        ?>

                                        <div class="form-group our-form-group check-apply">
                                            <?= $form->field($doOrderModel, 'apply', [
                                                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                                'errorOptions' => ['class' => 'help-block-modal'],
                                                'inputOptions' => ['class' => 'modal-input']
                                            ])->checkbox([
                                                'label' => Yii::t('app', 'I_accept') . "&nbsp" . Html::a(Yii::t('app', 'user_Agreement'), '/site-rules')
                                            ])
                                            ?>
                                        </div>

                                        <?= Html::submitInput(Yii::t('app', 'Оплатить'), [
                                            'class' => 'cart-submit btn btn-solid',
                                            'name' => 'order-button',
                                            'id' => 'order-button',
                                            'disabled' => false,
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>

                        <div class="cart-checkout-navigation-controls">
                            <a href="<?= Url::to(['/shopping-cart/clear-shopping-cart']) ?>" class="del btn btn-solid" onClick="return window.confirm('Вы уверены?');" id="clear-basket">
                                Очистить корзину
                            </a>
                            <a href="#" class="prev btn btn-outline-color hidden">Назад</a>
                            <a href="#" class="next btn btn-solid">Вперед</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
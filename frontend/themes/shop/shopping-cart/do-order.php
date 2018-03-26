<?php
/**
 * @var $model \common\models\Orders
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\PaymentType;
use common\models\Region;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/**@var $this \yii\web\View*/

$this->registerCssFile('css/new/startuply.css');
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="listings-row" style="padding: 20px 0 20px 0;">

                            <div class="form-group">
                                <h1><?= Html::encode('Форма заказа') ?></h1>
                            </div>

                            <div>
                                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                                <?= $form->field($model, 'idUser')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false) ?>

                                <?= $form->field($model, 'idRegion')->widget(Select2::className(), [
                                    'data' => ArrayHelper::map(Region::find()->select(['id', 'title'])->orderBy('title')->all(), 'id', 'title'),
                                    'options' => [
                                        'placeholder' => 'Выберите область ...',
                                        'id' => 'idRegion',
                                    ],
                                    'pluginOptions' => ['allowClear' => true],
                                ]) ?>
                                <?= $form->field($model, 'idCity')->widget(DepDrop::className(), [
                                    'data' => [],
                                    'type' => DepDrop::TYPE_SELECT2,
                                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                    'pluginOptions' => [
                                        'placeholder' => 'Выберите город ...',
                                        'depends' => ['idRegion'],
                                        'url' => Url::to(['/site/city']),
                                        'loadingText' => 'Загрузка городов ...',
                                    ],
                                ]) ?>

                                <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

                                <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>
                                <?= $form->field($model, 'fio')->textInput(['maxlength' => 255]) ?>

                                <?php
//                                 echo $form->field($model, 'paymentType')->widget(Select2::className(), [
//                                    'data' => ArrayHelper::map(PaymentType::find()->select(['id', 'title'])->all(), 'id', 'title'),
//                                    'options' => ['placeholder' => 'Выберите тип оплаты'],
//                                    'pluginOptions' => ['allowClear' => true],
//                                ])
                                ?>

                                <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
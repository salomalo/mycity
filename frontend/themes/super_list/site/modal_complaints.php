<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\City;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\PasswordResetRequestForm $model
 */
?>
<div class="modal-dialog our-modal-dialog pass-send">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <!--<h4 class="modal-title our-modal-title" id="myModalLabel"></h4>-->
            <h4><?= Yii::t('app', 'Complaints_and_suggestions') ?></h4>
        </div>

        <div class="modal-body our-modal-body">
            <div class="descr">
                <p><?= Yii::t('app', 'Complaints_text') ?> </p>
                <br>
            </div>
            
            <?php
            $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableClientValidation' => 'true',
                        'action' => \Yii::$app->urlManager->createUrl(['site/showmodal-complaint']),
            ]);
            ?>
            
            <?= $form->field($model, 'idCity')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(City::find()->where(['id' => Yii::$app->params['activeCitys']])->all(), 'id', 'title'),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select_city'),
                        'id' => 'idCity',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                    ]
                ])->label('');
            ?>
          
            <?=
            $form->field($model, 'name', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                //'errorOptions' => ['class' => 'help-block alert alert-danger', 'style' => 'display:none;'],
                'inputOptions' => ['placeholder' => Yii::t('app', 'Your_name') . ' ...', 'class' => 'modal-input']
                    ]
            )->label(false)
            ?>
            
            <?=
            $form->field($model, 'email', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                //'errorOptions' => ['class' => 'help-block alert alert-danger', 'style' => 'display:none;'],
                'inputOptions' => ['placeholder' => 'Ваш email ...', 'class' => 'modal-input']
                    ]
            )->label(false)
            ?>
            
            <?=
            $form->field($model, 'text', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                //'errorOptions' => ['class' => 'help-block alert alert-danger', 'style' => 'display:none;'],
                'inputOptions' => ['placeholder' => Yii::t('app', 'Text_message') . ' ...', 'class' => 'modal-textarea']
                    ]
            )->textarea()->label(false)
            ?>

            
            <div class="modal-footer our-modal-footer">
                <div class="form-group our-form-group">
                    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'modal-button but-r send-button', 'name' => 'send-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Friend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="friend-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => ArrayHelper::map(User::find()->orderBy('id ASC')->all(),'id','username'),
        'options' => [
            'placeholder' => 'Select a user ...',
            'id' => 'idUser',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);
    ?>

    <?= $form->field($model, 'idFriend')->widget(Select2::className(),[
        'data' => ArrayHelper::map(User::find()->orderBy('id ASC')->all(),'id','username'),
        'options' => [
            'placeholder' => 'Select a friend ...',
            'id' => 'idFriend',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);
    ?>

    <?= $form->field($model, 'status')->widget(Select2::className(),[
        'data' => User::$types,
        'options' => [
            'placeholder' => 'Select a status ...',
            'id' => 'status',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

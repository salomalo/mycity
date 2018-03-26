<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\SignupForm $model
 */
$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup', 
                'options' => ['enctype'=>'multipart/form-data'],
                ]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'password2')->passwordInput() ?>
                <?= $form->field($model, 'type') ?>
                <?= $form->field($model, 'photoUrl')->fileInput(); ?>
                <?= $form->field($model, 'vkID') ?>
                <?= $form->field($model, 'vkToken') ?>
                <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter birth date ...'],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-M-yyyy'
                    ]
                ]); ?>
            
                <?= $form->field($model, 'description') ?>
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

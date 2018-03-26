<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\PasswordResetRequestForm $model
 */
?>

<?php if(\Yii::$app->getSession()->has('success')): ?>
    <script>
        $.ajax({
            type:'POST',
            data: 'email='+ "<?php echo \Yii::$app->getSession()->getFlash('success')?>",
            url:'<?=Url::to("site/reset-success")?>',
            success: function(data)
                {
                    $('#myModal').html(data);
                    $('#myModal').modal();
                }
        });
    </script>
       <?php \Yii::$app->getSession()->remove('success'); ?>
<?php endif;?>
                    
<?php if(\Yii::$app->getSession()->has('error')): ?>
    <script>
        $.ajax({
            type:'POST',
            data: 'email='+ "<?php null?>",
            url:'<?=Url::to("site/reset-success")?>',
            success: function(data)
                {
                    $('#myModal').html(data);
                    $('#myModal').modal();
                }
        });
    </script>
       <?php \Yii::$app->getSession()->remove('error'); ?>
<?php endif;?>
    
<div class="modal-dialog our-modal-dialog">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <!--<h4 class="modal-title our-modal-title" id="myModalLabel"></h4>-->
            <h4><?= Yii::t('app', 'Forgot_your_password') ?></h4>
            <h5><?= Yii::t('app', 'If_you_forget_the_password') ?></h5>
            <div class="text"><?= Yii::t('app', 'forget_user_text') ?></div>
        </div>

        <div class="modal-body our-modal-body">
            <h1><?= Html::encode($this->title) ?></h1>


            <?php $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
                'action' => \Yii::$app->urlManager->createUrl(['site/showmodal-password-reset']),
                ]); ?>
                <?= $form->field($model, 'email', [
                    'template' => "{error}\n{input}\n{label}\n{hint}\n",
                    'errorOptions' => ['class' => 'help-block-modal'],
                    'inputOptions' => ['placeholder' => 'Ваш email...', 'class' => 'modal-input'
                        ]
                    ])->label('') ?>
			<div class="modal-footer our-modal-footer">
            <div class="form-group">
			&larr; <?= Html::a(Yii::t('app', 'Back_I_remembered_your_password'), ['javascript:void(0);'], ['onclick'=>'closeModal();return false;', 'class'=>'back-pass']); ?>         
            <?= Html::submitButton(Yii::t('app', 'Forgot_your_password'), ['class' => 'modal-button but-r reset-button']) ?>
            </div>
<?php ActiveForm::end(); ?>
			</div>
        </div>
    </div>
</div>
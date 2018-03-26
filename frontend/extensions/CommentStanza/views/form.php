<?php
/**
 * @var $this \yii\web\View
 * @var $mongo boolean
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<!--<form id="contact-form" name="contact-form" method="post" action="email.php" class="clearfix">-->
<!--    <p class="form-group">-->
<!--        <textarea id="message" name="message" tabindex="3" rows="3" placeholder="Message *" required="" class="form-control"></textarea>-->
<!--    </p>-->
<!--    <p class="text-right">-->
<!--        <button type="submit" id="sending" class="btn-custom-3" data-send="Sending...">-->
<!--            Send Message-->
<!--        </button>-->
<!--    </p>-->
<!--</form>-->

<?php if (Yii::$app->user->identity) : ?>
    <?php $form = ActiveForm::begin(['id' => 'form-comment-add', 'class' => 'clearfix', 'method' => 'post', 'action' => ['comment/create-ads']]); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6])->label('') ?>

    <?php if($mongo) : ?>
        <?= Html::hiddenInput('Comment[pidMongo]', $id) ?>
    <?php else: ?>
        <?= Html::hiddenInput('Comment[pid]', $id)?>
    <?php endif; ?>

    <?= Html::hiddenInput('Comment[type]', $type) ?>

    <?= Html::hiddenInput('Comment[idUser]', \Yii::$app->user->identity->id) ?>

    <p class="text-right">
        <button type="submit" id="sending" class="btn-custom-3" data-send="Sending...">
            Оставить отзыв
        </button>
    </p>

    <?php ActiveForm::end(); ?>
<?php else : ?>
    <p>
        <br>
    <h3>
        <?= Yii::t('widgets', 'only_authorized_{authorized}', [
            'authorized' => Html::a(Yii::t('widgets', 'authorized'), ['/'], [
                'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;'
            ])
        ]) ?>
    </h3>
    </p>
<?php endif; ?>

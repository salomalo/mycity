<?php
/**
 * @var $model \common\models\Comment
 * @var $type
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php if (Yii::$app->user->identity) : ?>
    <div class="comment-form">
        <?php $form = ActiveForm::begin(['id'=>'form-comment-add']); ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6])->label('') ?>

        <?= Html::hiddenInput('Comment[pid]', $id)?>

        <?= Html::hiddenInput('Comment[type]', $type)?>

        <?= Html::hiddenInput('Comment[idUser]', \Yii::$app->user->identity->id)?>

        <div class="form-submit">
            <button type="submit" class="rating-form-submit">
                <span class="add"><?= Yii::t('widgets', 'comment_on') ?></span>
                <span class="edit"><?= Yii::t('widgets', 'save_changes') ?></span>
            </button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
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
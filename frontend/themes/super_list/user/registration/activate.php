<?php

/**
 * @var yii\web\View $this
 * @var string $email
 */
if (Yii::$app->session->hasFlash('frontendRegister')) {
    $this->registerJs("yaCounter33738289.reachGoal('FRONTEND_REGISTRATION');", yii\web\View::POS_LOAD);
}
?>
<div class="modal-dialog our-modal-dialog pass-send">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal" >
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <h4><?= Yii::t('app', 'Sign_up_activate_title')?></h4>
            <h5><?= Yii::t('app', 'Sign_up_activate_mail_{email}', ['email' => $email])?></h5>
        </div>

        <div class="modal-body our-modal-body pass-ok">
            <h4><?= Yii::t('app', 'Whats_next')?></h4>
            <div class="descr">
                <p><?= Yii::t('app', 'Sign_up_activate_text_{email}', ['email' => $email])?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * @var yii\web\View $this
 * @var string $email
 */
?>

<div class="opacity-square">
    <div class="modal-header our-modal-header">
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
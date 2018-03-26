<?php

use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\PasswordResetRequestForm $model
 * @var $text string
 */
?>
<div class="modal-dialog our-modal-dialog pass-send">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <!--<h4 class="modal-title our-modal-title" id="myModalLabel"></h4>-->
            <h4><?= Yii::t('app', 'Post') ?></h4>
        </div>

        <div class="modal-body our-modal-body">
            <div class="descr">
			<p><?= $text ?></p>
             </div>

        </div>
    </div>
</div>

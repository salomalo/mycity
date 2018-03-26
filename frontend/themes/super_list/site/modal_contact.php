<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
            <h4><?= Yii::t('app', 'Our_contacts') ?></h4>
        </div>

        <div class="modal-body our-modal-body">
            <div class="descr">
                <p><?= Yii::t('app', 'To_email') ?> <?= Html::a(Yii::$app->params['contactEmail'], 'mailto:' . Yii::$app->params['contactEmail']) ?></p>
             </div>

        </div>
    </div>
</div>

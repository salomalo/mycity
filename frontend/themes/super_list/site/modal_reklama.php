<?php
/**
 * @var yii\web\View $this
 */

use yii\helpers\Html;
?>

<div class="modal-dialog our-modal-dialog pass-send">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4><?= Yii::t('app', 'Advertising_on_the_website') ?></h4>
        </div>

        <div class="modal-body our-modal-body">
            <div class="descr">
                <p><?= Yii::t('app', 'To_reklama') ?> <?= Html::a(Yii::$app->params['adEmail'], 'mailto:' . Yii::$app->params['adEmail']) ?></p>
            </div>

        </div>
    </div>
</div>
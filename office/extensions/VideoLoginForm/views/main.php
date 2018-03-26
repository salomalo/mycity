<?php
/**
 * @var $this \yii\web\View
 * @var $config string
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

use office\extensions\Counters\Counters;
?>

<div class="video-block-content">
    <div id="video-block">

        <div class="pattern"></div>

        <div class="row" id="video-login">
            <div class="col-md-4 col-md-offset-4">
                <?= $this->render('_form', ['model' => $model, 'module' => $module]) ?>

                <div class="text-center">
                    <a href="http://citylife.info" class="btn btn-primary"><?= Yii::t('app', 'Come_back_to_site') ?></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div id="footer">
                    <div class="footer-block counter" style="margin-top: 50px;">
                        <div class="footer-title"><?= Yii::t('app', 'We_believe') ?> <i class="fa fa-long-arrow-down"></i></div>
                        <?= Counters::widget(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="video" class="player" data-property="<?= $config ?>"></div>
</div>
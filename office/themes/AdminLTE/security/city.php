<?php
/**
 * @var $user \common\models\User
 * @var $this \yii\web\View
 */

use common\models\City;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="row" style="margin-top: 50px">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('app', 'Set a city') ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin() ?>

                <?= $form->field($user, 'city_id')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(Yii::$app->params['cities'][City::ACTIVE], 'id', 'title')
                ]) ?>

                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
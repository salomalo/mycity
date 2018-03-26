<?php
/**
 * @var $form ActiveForm
 * @var $model Afisha
 */
use common\models\Afisha;
use common\models\AfishaWeekRepeat;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;

?>
<?= $form->field($model, 'repeat')->dropDownList(Afisha::$repeat_type) ?>

<div id="repeat-days" <?= !$model->repeatDays ? 'class="hidden"' : ''?>>
    <?= $form->field($model, 'repeatDays')->widget(Select2::className(), [
        'data' => AfishaWeekRepeat::$days,
        'options' => [
            'placeholder' => 'Select a days ...',
            'id' => 'repeatDays',
            'multiple' => true,
        ],
    ]); ?>
</div>

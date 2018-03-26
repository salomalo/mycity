<?php
/**
 * @var City $modelCity
 */

use common\models\City;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin(['id' => 'form-city', 'action' => Url::to(['site/city']), 'type' => ActiveForm::TYPE_INLINE]); ?>


<?= $form->field($modelCity, 'id')->widget(Select2::className(), [
        'data' => Yii::$app->params['adminCities']['select'],
        'options' => [
            'placeholder' => 'Выберите город ...',
            'id' => 'city-id',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '200px',
        ],
        'pluginEvents' => [
            "select2:unselect" => "function() { 
                $.ajax({
                    type:'POST',
                    url: '/site/city',
                    async: false,
                    dataType: 'json',
                    data: 'do=clear',     
                    success: function(responce) {}
                }); 
            }"
        ]
    ])->label('') ?>

<?php ActiveForm::end(); ?>
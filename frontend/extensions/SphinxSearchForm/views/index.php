<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$data = ArrayHelper::map($cityList, 'id', 'title');
$this->registerJsFile('../js/new/bootstrap-select.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$data[0] = 'Главная';
?>

<br>

<?php 
    $form = ActiveForm::begin([
        'action' => Url::to(['/search/index']),
        'method' => 'GET',
        'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_INLINE
    ]); 
?>
   
    <?= Html::input('text', 's', $s, [
            'placeholder' => Yii::t('business', 'Enter_search_phrase'),
            'class' => 'form-control'
        ]) ?>

    <div class="form-group field-sphinxsearchform-id_city">
        <?= Select2::widget([
            'name' => 'id_city',
            'data' => $data,
            'value' => $id_city,
            'options' => [
                'placeholder' => 'Выберите город',
                'multiple' => false
            ],
            'pluginOptions' => ['allowClear' => true]
        ]);?>
    </div>

    <div class="form-group field-sphinxsearchform-id_type">
        <?= Select2::widget([
            'name' => 'type',
            'data' => $typeList,
            'value' => $type,
            'options' => [
                'placeholder' => 'Выберите тип',
                'multiple' => false
            ],
            'pluginOptions' => ['allowClear' => true]
        ]);?>
    </div>
    
    <?= Html::hiddenInput('page', $page) ?>
    <?= Html::submitButton(Yii::t('action', 'Search_btn'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
<br>

<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use common\models\Business;
use common\models\AfishaCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;

/**
 * @var $this yii\web\View
 * @var $model common\models\Afisha
 * @var $form yii\widgets\ActiveForm
 * @var $idCompany integer
 */
?>

<div class="afisha-form">
    <?= LanguageWidget::widget() ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label("Заголовок") ?>

    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(AfishaCategory::find()->where(['isFilm' => 0])->all(),'id','title'),
        'options' => [
            'placeholder' => Yii::t('afisha', 'Select_category'),
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?php if($model->image):?>
        <img src="<?= Yii::$app->files->getUrl($model, 'image', 70) ?>">
        <a href="<?= Url::to(['afisha/update', 'id' => $model->id, 'idCompany' => $idCompany, 'actions'=>'deleteImg']) ?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput()->label("Картинка") ?>

    <?php if ($idCompany) : ?>
        <?= $form->field($model, 'idsCompany')->hiddenInput(['value'=> $idCompany])->label(false) ?>
    <?php else : ?>
        <?= $form->field($model, 'idsCompany')->widget(Select2::className(),[
            'data' => ArrayHelper::map(Business::find()
                ->where(['idUser' => Yii::$app->user->id])
                ->andWhere(['type' => null])
                ->orWhere('type > :type', [':type' => Business::TYPE_KINOTHEATER])
                ->limit(100)
                ->all(),'id','title'),
            'options' => [
                'placeholder' => 'Выберите предприятие ...',
                'id' => 'idsCompany',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label("Описание") ?>

    <?= $form->field($model, 'dateStart')->widget(DatePicker::className(), [
    'options' => ['placeholder' => Yii::t('afisha', 'Select_date_start')],
    'pluginOptions' => [
        'autoclose'=>true,
        'todayHighlight' => true,
        'language' => 'ru',
        'format' => 'yyyy-mm-dd'
    ]
    ])->label(Yii::t('afisha', 'Date_start'));
    ?>

    <?= $form->field($model, 'dateEnd')->widget(DatePicker::className(), [
        'options' => ['placeholder' => Yii::t('afisha', 'Select_date_end')],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-mm-dd'
        ]
    ])->label(Yii::t('afisha', 'Date_end')) ?>
    
    <div class="form-group field-afisha-times">
    <label class="control-label" for="afisha-times"><?= Yii::t('afisha', 'Afisha_times') ?></label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'times',
            'options' => ['multiple' => true, 'placeholder' => '12:00'],
            'pluginOptions' => [
                'tags' => [],
                'maximumInputLength' => 5,
            ],
        ]) ?>
    </div>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

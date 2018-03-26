<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Action
 * @var $form yii\widgets\ActiveForm
 */

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\Business;
use kartik\widgets\DatePicker;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\ActionCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
?>

<div class="action-form">
    <?= LanguageWidget::widget() ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?php if ($model->idCompany) : ?>
        <?= $form->field($model, 'idsCompany')->hiddenInput(['value'=> $model->idCompany])->label(false) ?>
    <?php else : ?>
        <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
            'data' => ArrayHelper::map(Business::find()
                ->where(['idUser' => Yii::$app->user->id])
                ->andWhere(['type' => null])
                ->orWhere('type > :type', [':type' => Business::TYPE_KINOTHEATER])
                ->limit(100)
                ->all(),'id','title'),
            'options' => ['placeholder' => 'Выберите предприятие ...', 'id' => 'idCompany'],
            'pluginOptions' => ['allowClear' => true]
        ]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>


    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(ActionCategory::find()->all(),'id','title'),
        'options' => [
            'placeholder' => 'Выберите категорию ...',
            'id' => 'idCategory',
            'multiple' => false ,
        ],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Категория ');?>
    
    <?php if($model->image):?>
        <img src="<?= Yii::$app->files->getUrl($model, 'image', 195) ?>" >
        <a href="<?= Url::to(['action/update', 'id' => $model->id, 'actions'=>'deleteImg']) ?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput()->label('Картинка') ?>

    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label('Описание');
    ?>

    <?= $form->field($model, 'price')->textInput()->label('Цена') ?>
    
    <?= $form->field($model, 'dateStart')->widget(DatePicker::className(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
        ]
    ])->label('Дата начала акции') ?>
    
    <?= $form->field($model, 'dateEnd')->widget(DatePicker::className(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
        ]
    ])->label('Дата окончания акции') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
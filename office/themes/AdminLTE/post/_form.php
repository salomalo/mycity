<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\Business;
use common\models\Post;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\PostCategory;
use common\extensions\MultiView\MultiView;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\models\File;
use common\models\City;
//use mihaildev\elfinder\InputFile;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\extensions\fileUploadWidget\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
/* @var $business Business */
?>

<div class="post-form">
    <?= LanguageWidget::widget() ?>

    <?php
    if (!$model->isNewRecord) {
        echo FileUploadUI::widget([
            'model' => $model,
            'attribute' => 'name',
            'type' => File::TYPE_POST,
            'essence' => $this->context->id,
            'gallery' => false,
            'fieldOptions' => ['accept' => 'image/*'],
            'clientOptions' => ['maxFileSize' => Yii::$app->params['maxSizeUpload']]
        ]);
    }
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= Html::activeHiddenInput($model, 'status', ['value' => Post::TYPE_PUBLISHED]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label("Заголовок") ?>

    <?php
    $model->business_id = $business->id;
    ?>
    <?= $form->field($model, 'business_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Business::find()->where(['id' => $business->id])->all(), 'id', 'title'),
        'options' => [
            'disabled' => false,
            'placeholder' => 'Выберите предприятие ...',
            'id' => 'business_id',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => false],
    ])->label("Предприятие") ?>

    <?= $form->field($model, 'idCity')->widget(Select2::className(), [
        'data' => City::getAll(['id' => Yii::$app->params['activeCitys']]),
        'options' => [
            'placeholder' => 'Выберите город ...',
            'id' => 'idCity',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ])->label("Город") ?>
    
    <?= $form->field($model, 'idUser')->hiddenInput(['value'=> Yii::$app->user->id])->label('') ?>

    <?= $form->field($model, 'idCategory')->widget(Select2::className(), [
        'data' => ArrayHelper::map(PostCategory::find()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => 'Select a category ...',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label("Категория") ?>

    <?php if ($model->image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 90)) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['post/update', 'id' => $model->id, 'actions' => 'deleteImg'], [
            'title' => 'Delete',
            'data-confirm' => "Выуверены что хотите удалить?",
        ]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label("Картинка") ?>
        
    <div class="form-group field-post-video">
    <label class="control-label" for="post-video">Видео</label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'video',
            'options' => ['multiple' => true, 'placeholder' => 'Используйте ID видео youtube, например: BPNTC7uZYrI'],
            'pluginOptions' => ['tags' => []],
        ]) ?>
    </div>

    <?= $form->field($model, 'shortText')->widget(CustomCKEditor::className())->label("Краткое описание") ?>

    <?= $form->field($model, 'fullText')->widget(CustomCKEditor::className())->label("Полный текст") ?>

    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>

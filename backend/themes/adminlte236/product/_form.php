<?php
use common\extensions\CustomCKEditor\CustomCKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ProductCompany;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\extensions\MultiSelect\MultiSelect;
use common\models\Sitemap;
/**
 * @var yii\web\View $this
 * @var common\models\Product $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['id' => 'productForm', 'options' => ['enctype'=>'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    <?= Html::hiddenInput('action', 0, ['id' => 'formAction']) ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'url') ?>
    
    <?php if ($model->image) : ?>
        <img src="<?= Yii::$app->files->getUrl($model, 'image', 100) ?>"> 
        <a href="<?= Yii::$app->urlManager->createUrl(['product/update', 'id' => (string)$model->_id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif; ?>
    
    <?= $form->field($model, 'image')->fileInput() ?>
    
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()) ?>
    
    <?php if (!empty($model->gallery) && !$model->isNewRecord) : ?>
       <?php foreach (Yii::$app->files->getGallery($model, 'gallery', 100) as $item) : ?>
            <img src="<?= $item ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'gallery[]')->widget(FileInput::className(), [
        'options' => ['multiple' => true, 'accept' => 'image/*'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showUpload' => false
        ],
    ]) ?>

    <div class="form-group field-product-video">
    <label class="control-label" for="product-video">Video</label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'video',
            'options' => [ 'placeholder' => 'Enter video hash ...'],
        ]) ?>
    </div>

    <?= $form->field($model, 'idCategory')->widget(MultiSelect::className(), [
        'url'=>'/product-category/product-category-list',
        'className' => 'common\models\ProductCategory',
        'options' => ['placeholder' =>'Выберите категорию ...'],
        'multiple' => false
    ]) ?>
    
    <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
        'data' => ArrayHelper::map(ProductCompany::find()->all(),'id','title'),
        'options' => ['placeholder' => 'Select a company ...'],
    ]) ?>

    <?= $form->field($model, 'model') ?>

    <div style="border: 1px solid #000000;padding: 5px; margin-bottom: 15px;">
        <ul>
            <?php if($model->idCategory):?>
                <?php foreach(ProductCustomfield::findAll(['idCategory' => $model->idCategory]) as $cf):?>
                    <li>
                        <span><?=$cf->title?></span><br>
                    <?php
                    if($cf->type == $cf::TYPE_DROP_DOWN){
                    echo Html::activeDropDownList($model, $cf->alias, ArrayHelper::map(ProductCustomfieldValue::findAll(['idCustomfield' => $cf->id]),'value','value'),['class'=>'form-control']);}
                    else {
                        if($model->isNewRecord){
                            $cfVal = ProductCustomfieldValue::find()->where(['idCustomfield' => $cf->id])->one();
                            echo Html::activeInput('text', $model, $cf->alias,[
                                'value'=>$cfVal['value'],
                                'class'=>'form-control',
                            ]);
                        } else {
                            echo Html::activeInput('text', $model, $cf->alias,[
                                'value'=>$model[$cf->alias],
                                'class'=>'form-control',
                            ]);
                        }
                    }
                    ?>
                    </li>
                <?php endforeach ?>
            <?php endif;?>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default collapsed-box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'seo_title')->textInput() ?>
                    <?= $form->field($model, 'seo_description')->textarea() ?>
                    <?= $form->field($model, 'seo_keywords')->textInput() ?>

                    <?= $form->field($model, 'sitemap_en')->checkbox() ?>
                    <?= $form->field($model, 'sitemap_priority')->widget(Select2::className(), [
                        'data' => Sitemap::$priority,
                        'options' => ['placeholder' => 'Выберите приоритет'],
                        'pluginOptions' => ['allowClear' => true]
                    ]) ?>
                    <?= $form->field($model, 'sitemap_changefreq')->widget(Select2::className(), [
                        'data' => Sitemap::$changefreq,
                        'options' => ['placeholder' => 'Выберите частоту изменения'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id' => 'formSubmit']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

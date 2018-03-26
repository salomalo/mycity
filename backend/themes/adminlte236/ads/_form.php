<?php
/**
 * @var yii\web\View $this
 * @var common\models\Ads $model
 * @var yii\widgets\ActiveForm $form
 */

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\extensions\FilesUpload\FilesUpload;

use common\models\Ads;
use common\models\AdsColor;
use common\models\Business;
use common\models\City;
use common\models\ProductCategory;
use common\models\ProductCompany;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use common\models\User;
use console\models\Arenda;
use kartik\color\ColorInput;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$idCompanyName = 'xxx';
$company = empty($model->idCompany) ? null : ProductCompany::findOne($model->idCompany);

if ($company) {
    $idCompanyName = $company->title;
}

$script = <<< JS
        $(function () {
            $('#sub-user-id').on('change', function (event) {
                var id = $(this).attr('id'), val = $(this).val();
                if (val){
                    $.ajax({
                        url: '/business/get-phone',
                        type: 'post',
                        data: {
                            businessId : val,
                            _csrf: csrfVar
                        },
                        success: function (data) {
                            $('input#ads-contact').val(data.phone);
                        }
                    });
                }
            });
        });
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
<head>
    <script>
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>
<div class="product-form">

    <?php $form = ActiveForm::begin(['id' => 'productForm', 'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    <?= Html::hiddenInput('action', 0, ['id' => 'formAction']) ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'isShowOnBusiness', [
        'template' => '{label}<br/>{input}{error}{hint} '
    ])
        ->checkbox(['label' => ''])
        ->label("Показывать в приоритете на странице предприятия")
    ?>
    
    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(), [
        'data' => User::getAll(),
        'options' => [
            'placeholder' => 'Select a user ...',
            'id' => 'productUser',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?php if ($model->isNewRecord) : ?>
        <?= $form->field($model, 'idBusiness')->widget(DepDrop::className(), [
            'options' => ['id'=>'sub-user-id'],
            'type' => DepDrop::TYPE_SELECT2,
            'pluginOptions'=>[
                'depends'=>['productUser'],
                'placeholder'=>'Select...',
                'url'=> Url::to(['/business/business-by-user'])
            ],
//            'pluginEvents' => [
//                "depdrop.change"=>"function(event, id, value, count) { console.log(id); console.log(value); console.log(count); }",
//            ]
        ]);?>
    <?php else : ?>
        <?= $form->field($model, 'idBusiness')->widget(DepDrop::className(), [
            'options' => ['id'=>'sub-user-id'],
            'data' => ArrayHelper::map(Business::find()->where(['idUser' => $model->idUser])->all(),'id','title'),
            'type' => DepDrop::TYPE_SELECT2,
            'pluginOptions'=>[
                'depends'=>['productUser'],
                'initialize' => true,
                'initDepends'=>['productUser'],
                'placeholder'=>'Select...',
                'url'=> Url::to(['/business/business-by-user'])
            ],
//            'pluginEvents' => [
//                "depdrop.change"=>"function(event, id, value, count) { console.log(id); console.log(value); console.log(count); }",
//            ]
        ]);?>
    <?php endif ?>

    <?= $form->field($model, 'idCity')->widget(DepDrop::className(), [
        'data' => City::getAll(['main' => City::ACTIVE]),
        'type' => DepDrop::TYPE_SELECT2,
        'options' => [
            'id' => 'idCity',
            'width' => '300px',
        ],
        'pluginOptions' => [
            'url' => Url::to(['/business/city-by-business']),
            'depends' => ['sub-user-id'],
            'placeholder' => 'Выберите город',
        ]
    ])->label('Город') ?>

    <?= $form->field($model, 'idCategory')->widget(Select2::className(), [
        'data' => ProductCategory::getCategoryArray(),
        'options' => ['placeholder' => 'Выберите Категорию...'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Категория товара'); ?>

    <?= $form->field($model, 'idCompany')->widget(DepDrop::className(), [
        'data' => ProductCategory::getAll($model->idCategory),
        'options' => [],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'initialize' => true,
            'initDepends'=>['ads-idcategory'],
            'depends' => ['ads-idcategory'],
            'url' => Url::to(['/business/get-all-product-category']),
            'loadingText' => 'Подождите...',
            'placeholder' => 'Выберите Производителя...',
        ],
    ])->label('Производитель'); ?>

    <?php if ($model->image) : ?>
    <?php
    /** @noinspection PhpUndefinedFieldInspection */
    $img = Yii::$app->files->getUrl($model, 'image', 100);
    $url = Url::to(['ads/update', 'id' => (string)$model->_id, 'actions' => 'deleteImg']);
    ?>
    <img src="<?= $img ?>">
    <a href="<?= $url ?>" title="Delete" data-confirm="Are you sure you want to delete this item?">
        <span class="glyphicon glyphicon-trash"></span>
    </a>

    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput(); ?>

    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()) ?>


            
    <?php /*
    $form->field($model, 'idBusiness')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Business::find()->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a business ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);*/ ?>
    <?php //Customfield old ?>
    <?php /*
    <?php if ($model->model) : ?>
    <div style="border: 1px solid #000000;padding: 5px; margin-bottom: 15px;">
        <ul>
            <?php $categoryFields = ProductCustomfield::findAll(['idCategory' => $model->idCategory]); ?>
            <?php foreach ($categoryFields as $cf) : ?>
            <li>
                <span><?=$cf->title?></span><br>
            <?php
            if ($model->isNewRecord) {
                $modelProd = Product::find()->where(['_id' => $model->model])->one();
                if ($cf->type == $cf::TYPE_DROP_DOWN) {
                    echo Html::activeDropDownList(
                        $modelProd,
                        $cf->alias,
                        ArrayHelper::map(
                            ProductCustomfieldValue::findAll(['idCustomfield' => $cf->id]),
                            'value',
                            'value'
                        ),
                        ['class'=>'form-control']
                    );
                } else {
                    $cfVal = ProductCustomfieldValue::findOne(['idCustomfield' => $cf->id]);
                    echo Html::activeInput(
                        'text',
                        $modelProd,
                        $cf->alias,
                        [
                            'value'=>$cfVal['value'],
                            'class'=>'form-control',
                        ]
                    );
                }
            } elseif ($cf->type == $cf::TYPE_DROP_DOWN) {
                    echo Html::activeDropDownList(
                        $model,
                        $cf->alias,
                        ArrayHelper::map(
                            ProductCustomfieldValue::findAll(['idCustomfield' => $cf->id]),
                            'value',
                            'value'
                        ),
                        ['class'=>'form-control']
                    );
            } else {
                $cfVal = ProductCustomfieldValue::find()->where(['idCustomfield' => $cf->id])->one();
                echo Html::activeInput(
                    'text',
                    $model,
                    $cf->alias,
                    [
                        'value'=>$cfVal['value'],
                        'class'=>'form-control',
                    ]
                );
            }
            ?>
            </li>
        <?php endforeach ?>
    <?php elseif ($model->idCategory && $model->idCompany && !$model->model) : ?>
        <?php $categoryFields = ProductCustomfield::findAll(['idCategory' => $model->idCategory]); ?>
        <?php foreach ($categoryFields as $cf) : ?>
            <li>
                <span><?=$cf->title?></span><br>
            <?php
            if ($cf->type == $cf::TYPE_DROP_DOWN) {
                echo Html::activeDropDownList(
                    $model,
                    $cf->alias,
                    ArrayHelper::map(
                        ProductCustomfieldValue::findAll(['idCustomfield' => $cf->id]),
                        'value',
                        'value'
                    ),
                    ['class'=>'form-control']
                );
            } else {
                $cfVal = ProductCustomfieldValue::find()->where(['idCustomfield' => $cf->id])->one();
                echo Html::activeInput(
                    'text',
                    $model,
                    $cf->alias,
                    [
                        'value'=>$cfVal['value'],
                        'class'=>'form-control',
                    ]
                );
            }
            ?>
            </li>
        <?php endforeach ?>
        </ul>
    </div>
    <?php endif;?>
    <?php */ ?>
    <?php //Customfield arenda ?>

    <?php if ($model->idCategory) : ?>
        <div style="border: 1px solid #000000; padding: 5px; margin-bottom: 15px;">
            <ul>
                <?php $categoryFields = ProductCustomfield::findAll(['idCategory' => $model->idCategory]); ?>
                <?php foreach ($categoryFields as $cf) : ?>
                    <li>
                        <span><?=$cf->title?></span><br>
                        <?php if ($cf->type == $cf::TYPE_DROP_DOWN) : ?>

                            <?= Html::activeDropDownList($model, $cf->alias, ArrayHelper::map(
                                ProductCustomfieldValue::findAll(['idCustomfield' => $cf->id]),
                                'value',
                                'value'
                            ), ['class'=>'form-control']) ?>
                        <?php else : ?>
                            <?= Html::activeInput('text', $model, $cf->alias, [
                                'value'=> ArrayHelper::getValue(ProductCustomfieldValue::find()->select('value')->where(['idCustomfield' => $cf->id])->asArray()->one(), 'value'),
                                'class' => 'form-control',
                            ]) ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif;?>
    <?php //Customfield end; ?>

    <?php if (!$model->isNewRecord) :?>
        <div style="border: 1px solid;padding: 10px;">
            <?php
            $adsColor = AdsColor::find()->where(['idAds' => $model->_id])->andWhere(['isShowOnBusiness' => AdsColor::ENABLED])->all();
            ?>
            <?php foreach ($adsColor as $color) : ?>
                <?= Html::img(Yii::$app->files->getUrl($color, 'image', 100), ['class' => 'ads-main-image', 'style' => 'max-width: 100px; max-height: 100px;']) ?>
            <?php endforeach; ?>
            <?= Html::a('Цвета товара', Url::to(['/ads-color/index', 'adsId' => (string)$model->_id]), ['class' => 'btn btn-info']) ?>
        </div>
    <?php endif; ?>
    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'https://www.youtube.com/watch?v=LRt2jX1kaYo'])->label("Обзор товара") ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'discount')->widget(Select2::className(), [
        'data' => Ads::$typeDiscount,
        'options' => ['placeholder' => 'Выберте скидку ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Скидка');
    ?>

    <?= $form->field($model, 'contact')->textInput() ?>

    <?php //Простая галерея ?>
    <?= FilesUpload::widget(['model' => $model]) ?>

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
                    <?= $form->field($model, 'seo_description') ?>
                    <?= $form->field($model, 'seo_keywords') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id' => 'formSubmit']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

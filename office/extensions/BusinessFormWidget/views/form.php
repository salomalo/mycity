<?php
/**
 * @var $readOnly boolean
 * @var $endBody string
 * @var $init_address array
 * @var $model \common\models\Business
 */

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\BusinessCategory;
use common\models\City;
use common\models\ProductCategory;
use common\models\search\BusinessCategory as BusinessCategorySearch;
use common\models\UserPaymentType;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\extensions\BusinessPrice\BusinessPrice;
use yii\helpers\Url;

?>
<div class="business-form">
    <div class="box-header with-border">
        <i class="fa fa-bar-chart"></i>

        <h3 class="box-title">Тариф <?= $model::$priceTypes[$model->price_type] ?></h3>
    </div>
    <?= LanguageWidget::widget() ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label('Заголовок ' .  Html::a('?', '#', ['title' => 'Введите название'])) ?>

    <?= $form->field($model, 'price_type')->widget(Select2::className(), [
        'data' => $model::$priceTypes,
        'pluginOptions' => ['disabled' => true],
    ])->label('Тариф') ?>

    <?php if ($model->image) : ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 100)) ?>
        <?= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), ['business/update', 'id' => $model->id, 'actions' => 'deleteImg'], ['title' => 'Delete', 'data-confirm' => "Are you sure you want to delete this item?"]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label('Логотип предприятия'); ?>

    <?php if ($model->background_image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'background_image', 100)) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',
            ['business/update', 'id' => $model->id, 'actions' => 'deleteBackgroundImg'],
            ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']
        ) ?>
    <?php endif; ?>

    <?= $form->field($model, 'background_image')->fileInput()->label('Фон шапки' . Html::a('?', '#', ['title' => 'Будет отображаться на фоне при детальном просмотре предприятия. Изображение должно быть не меньше 200*500'])); ?>

    <?= $form->field($model, 'idUser')->hiddenInput(['value'=> $model->isNewRecord ? Yii::$app->user->id : $model->idUser])->label(false) ?>

    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'azdwsXLmrHE'])->label("Видео") ?>

    <?php
    $businessCategory = BusinessCategory::getCategoryList();
    unset($businessCategory[6645]);
    ?>
    <?= $form->field($model, 'idCategories')->widget(Select2::className(), [
        'model' => new BusinessCategorySearch(),
        'data' => $businessCategory,
        'attribute' => 'idCategories',
        'options' => [
            'placeholder' => 'Выберите категорию...',
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ])->label("Категории бизнеса " .  Html::a('?', '#', [
        'title' => 'Для максимальной эффективности как можно точнее определите категорию предприятия. Выберите категории, в которых ваше предприятие будет отображаться на сайте'
    ])) ?>

    <?php
    $idProductCategories = (count($model->idProductCategories) == 1 && empty($model->idProductCategories[0])) ? 0 : $model->idProductCategories;
    $item =  ArrayHelper::map(ProductCategory::find()->where(['id' => $idProductCategories])->all(), 'id', 'title');
    ?>
    <?= $form->field($model, 'idProductCategories')->widget(DepDrop::className(), [
        'model' => new \common\models\search\ProductCategory(),
        'data' => [$item],
        'type' => 2,
        'options' => [
            'multiple' => true,
            'width' => '300px',
        ],
        'pluginOptions' => [
            'allowClear' => false,
            'url' => '/business/business-category-by-business',
            'depends' => ['idCategory'],
            'initDepends' => ['idCategory'],
            'initialize' => true,
            'placeholder' => 'Выберите категорию',
        ],
    ])->label("Категории товаров " .  Html::a('?', '#', [
            'title' => 'Выберете категории товара которыми вы планируете торговать'
        ])) ;
    ?>

    <?= $form->field($model, 'user_payments')->widget(Select2::className(), [
        'data' => UserPaymentType::getAll(Yii::$app->user->id),
        'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        'options' => ['placeholder' => 'Выберите тип оплаты'],
    ])->label(Html::a('Способы оплаты ', Url::to(['/user-payment-type/create']), [
            'title' => 'Добавить способы оплаты',
            'style' => 'margin-bottom: 20px',
            'target' => '_blank',
        ]) .  Html::a('?', '#', [
    'title' => 'Способы оплаты - это то, как пользователь сможет оплатить вам ваши товары или услуги. Можно добавить в профиле.'
    ])) ?>

    <label class="control-label work-times">Время работы</label>

    <?= $this->render('_time', ['model' => $model]); ?>

    <?php
            if (!$readOnly) {
                echo '<div class="title-block-address">Адреса предприятия</div>';
            }
            echo Html::beginTag('div', ['id'=>'map-canvas', 'data-city' => $init_address]);
            echo Html::endTag('div');
    ?>
    <div style="width: 30%;margin-left: 20px;">
        <?= $form->field($model, 'idCity')->widget(Select2::className(), [
            'data' => ArrayHelper::map(City::find()->select(['id', 'title'])->where(['main' => City::ACTIVE])->all(), 'id', 'title'),
            'pluginOptions' => ['allowClear' => true],
            'options' => ['placeholder' => 'Выберите город'],
            'pluginEvents' => [
                "change" => "function(obj) { change_data_center(); change_hidden_input()}",
            ],
        ]) ?>
    </div>
    <?= $endBody ?>

    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label('Описание') ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'phone')->textarea(['rows' => '4'])->label('Телефон') ?>
    <?= $form->field($model, 'urlVK')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'urlFB')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'urlTwitter')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'skype')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'price')->fileInput()?>
    <?php
    echo BusinessPrice::widget(['model' => $model, 'isFrontend' => false])
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

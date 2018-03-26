<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Business;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\AfishaCategory;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Afisha */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Afisha';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afisha-index">

    <p>
        <?= Html::a('Create Afisha', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            'title',
            [
                'attribute' => 'idsCompany',
                'label' => 'Компании',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'idsCompany',
                    'options' => [
                        'placeholder' => 'Найдите и выберите предприятие',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                        ],
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['business/ajax-search']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ]),
                'value' => function ($model) {
                    return $model->companyNames($model->idsCompany);
                },
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'100px'],
                'filter' => false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img  width="100"  src=' . \Yii::$app->files->getUrl($model, 'image', 70) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'isChecked',
                'options' => ['width'=>'90px'],
                'value' => function($model){
                    return ($model->isChecked) ? 'Да' : 'Нет';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [false => 'Нет', true => 'Да'],
                    'attribute' => 'isChecked',
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'idCategory',
                'options' => ['width'=>'300px'],
                'value' => function ($model) {
                    return ($model->category)? $model->category->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => AfishaCategory::getAll(),
                    'attribute' => 'idCategory',
                    'options' => [
                        'placeholder' => 'Select a category ...',
                        'id' => 'idCategory',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            
//            [
//                'attribute' => 'idsCompany',
//                'value' => function ($model) {
//                    return ($model->idsCompany)? $model->companyNames($model->idsCompany) : '';
//                },
//                'filter'    => Select2::widget([
//                                'model' => $searchModel,
//                                'data' => ArrayHelper::map(Business::find()->all(),'id','title'),
//                                'attribute' => 'idsCompany',
//                                'options' => [
//                                        'placeholder' => 'Select a company ...',
//                                        'id' => 'idsCompany',
//                                        //'multiple' => true,
//                                        ],
//                                'pluginOptions' => [
//                                        'allowClear' => true,
//                                ]
//                            ]),
//            ],
//            'description:html',
            [
                'attribute' => 'dateStart',
                'options' => ['width'=>'170px'],
                'filter' =>false,
            ],
            // 'dateEnd',
            // 'rating',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

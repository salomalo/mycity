<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Business;
use common\models\ActionCategory;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Action */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-index">

    <p>
        <?= Html::a('Create Action', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'idCompany',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'idCompany',
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
                    return ($model->companyName)? $model->companyName->title : '';
                },

            ],
            [
                'attribute' => 'idCategory',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return ($model->category)? $model->category->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(ActionCategory::find()->all(),'id','title'),
                    'attribute' => 'idCategory',
                    'options' => [
                            'placeholder' => 'Select a category ...',
                            'id' => 'idsCategory',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'200px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img  width="100"  src=' . \Yii::$app->files->getUrl($model, 'image', 195) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'dateStart',
                'options' => ['width'=>'140px'],
                'filter' =>false,
            ],
            [
                'attribute' => 'dateEnd',
                'options' => ['width'=>'140px'],
                'filter' =>false,
            ],
            [
                'attribute' => 'description',
                'format' => 'text',
                'value' => function ($model) {
                    return html_entity_decode(strip_tags($model->description), ENT_QUOTES);
                },
            ],
            'price',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

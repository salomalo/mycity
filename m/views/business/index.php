<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\models\BusinessCategory;
use common\models\Business;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Business $searchModel
 */

$this->title = 'Мои Предприятия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-index">
    <p>
        <?= Html::a('Новое Предприятие', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => '№№',
                'options' => ['width'=>'80px'],
            ],
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
            ],
            [
                'attribute' => 'type',
                'label' => 'Тип',
                'value' => function ($model) {
                    return ($model->type)? Business::$types[$model->type] : '' ;
                }
            ],
            [
                'attribute' => 'image',
                'label' => 'Картинка',
                'format' => 'html',
                'filter' =>false,
                'options' => ['width'=>'150px'],
                'value' => function ($model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'idCategories',
                'label' => 'Категория предприятий',
                'value' => function ($model) {
                    return $model->categoryNames($model->idCategories);
                },
                'options' => ['width'=>'350px'],
                'filter'    => Select2::widget([
                                'model' => $searchModel,
                                //'data' => ArrayHelper::map(BusinessCategory::find()->all(),'id','title'),
                                'data' => BusinessCategory::getCategoryList(),
                                'attribute' => 'idCategories',
                                'options' => [
                                        'placeholder' => 'Выберите категорию ...',
                                        'id' => 'idCategories',
                                        //'multiple' => true,
                                        ],
                                'pluginOptions' => [
                                        'allowClear' => true,
                                ]
                            ]),
            ],
            [
                'attribute' => 'dateCreate',
                'label' => 'Дата создания',                
                'options' => ['width'=>'120px'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}',
            ],
        ],
    ]); ?>

</div>

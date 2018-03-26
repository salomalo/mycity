<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\search\Ads $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Ads';
$this->params['breadcrumbs'][] = $this->title;

//echo \yii\helpers\BaseVarDumper::dump(ArrayHelper::map($model, 'id','title', 'reg'), 10, true);
?>
<div class="product-index">


    <p>
        <?= Html::a('Create Ads', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => '_id',
                'options' => ['width'=>'250px'],
            ],
            'title',
            'idUser',
            [
                'attribute' => 'idCity',
                'value' => function ($model) {
                    return ($model->city) ? $model->city->title . ', ' . $model->city->region->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'idCity',
                    'options' => ['placeholder' => 'Select a city ...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'120px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img  width="100"  src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'idCategory',
                'label' => 'Категория',
                'value' => 'category.title',
                'options' => ['width'=>'350px'],
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

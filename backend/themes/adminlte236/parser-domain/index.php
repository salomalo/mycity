<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Region;
use common\models\City;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ParserDomain */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Parser Domains';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parser-domain-index">

    <p>
        <?= Html::a('Create Parser Domain', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idRegion',
                'value' => function ($model) {
                    return ($model->region) ? $model->region->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(Region::find()->all(),'id','title'),
                    'attribute' => 'idRegion',
                    'options' => [
                            'placeholder' => 'Select a region ...',
                            'id' => 'idRegion',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'idCity',
                'label' => 'City',
                'value' => function ($model) {
                    return ($model->city) ? $model->city->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(City::find()->all(),'id','title'),
                    'attribute' => 'idCity',
                    'options' => [
                            'placeholder' => 'Select a city ...',
                            'id' => 'idCity',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
               'label' => 'Id City',
                'value' => function ($model) {
                    return $model->idCity;
                }, 
            ],
            //'idCity',
            'domain',
            'mDomain',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

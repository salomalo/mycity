<?php

use common\models\WorkResume;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\WorkCategory;
use yii\helpers\ArrayHelper;
use common\models\City;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkResume */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Work Resumes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-resume-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Work Resume', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idCategory',
                'value' => function ($model) {
                    return isset($model->category->title) ? $model->category->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
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
            [
                'attribute' => 'idUser',
                'value' => function ($model) {
                    return isset($model->user->username) ? $model->user->username : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(User::find()->all(),'id','username'),
                    'attribute' => 'idUser',
                    'options' => [
                        'placeholder' => 'Select a category ...',
                        'id' => 'idUser',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'photoUrl',
                'format' => 'html',
                'filter' =>false,
                'value' => function ($model) {
                     if($model->photoUrl){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'photoUrl', 90) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'idCity',
                'value' => function (WorkResume $model) {
                    return $model->city ? $model->city->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'idCity',
                    'options' => ['placeholder' => 'Select a city ...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

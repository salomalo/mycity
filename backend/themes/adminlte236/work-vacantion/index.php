<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\WorkCategory;
use common\models\Business;
use yii\helpers\ArrayHelper;
use common\models\City;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkVacantion */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Work Vacantions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-vacantion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Work Vacantion', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idCity',
                'value' => function ($model) {
                    return isset($model->city->title) ? $model->city->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'idCity',
                    'options' => ['placeholder' => 'Select a city ...'],
                    'pluginOptions' => ['allowClear' => true]
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
                            'placeholder' => 'Select a user ...',
                            'id' => 'idUser',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            // 'title',
            // 'description:ntext',
            // 'proposition:ntext',
            // 'phone',
            // 'email:email',
            // 'skype',
            // 'name',
            // 'salary',
            // 'isFullDay',
            // 'isOffice',
            // 'experience:ntext',
            // 'male',
            // 'minYears',
            // 'maxYears',
            // 'dateCreate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

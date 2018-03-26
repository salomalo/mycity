<?php

use common\models\Business;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ParseKino */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Parse Kinos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-kino-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Parse Kino', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'remote_cinema_id',
            [
                'attribute' => 'local_cinema_id',
                'label' => 'Кинотеатр',
                'value' => function ($model) {
                    return $model->local_cinema_id ? \common\models\Business::findOne($model->local_cinema_id)->title : null;
                },
                'options' => ['width' => '150px'],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => $arrayBusiness,
                    'attribute' => 'local_cinema_id',
                    'options' => [
                        'placeholder' => 'Select business ...',
                        'id' => 'local_cinema_id',
                        //'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

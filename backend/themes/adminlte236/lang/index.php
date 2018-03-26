<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Lang */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Langs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lang-index">


    <p>
        <?= Html::a('Create Lang', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'url:url',
            'local',
            'name',
            
            [
                'attribute' => 'default',
                'options' => ['width'=>'200px'],
                'value' => function ($model) {
                    return ($model->default)? 'Да' : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [1 => 'Да', 0 => 'Нет'],
                    'attribute' => 'default',
                    'options' => [
                            'placeholder' => 'Select a default ...',
                            'id' => 'default',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            // 'date_update',
            // 'date_create',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\WorkCategory;
use common\models\City;
use common\models\Business;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkVacantion */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои вакансии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-vacantion-index">
    <p>
        <?= Html::a('Добавить вакансию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view grid-view-border-top'],
        'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
            ],

            [
                'attribute' => 'idCategory',
                'label' => 'Категория',
                'value' => function ($model) {
                    return $model->category->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
                    'attribute' => 'idCategory',
                    'options' => [
                        'placeholder' => 'Выберите категорию ...',
                        'id' => 'idCategory',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'idCity',
                'label' => 'Город',
                'value' => function ($model) {
                    return $model->city->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(City::find()->all(),'id','title'),
                    'attribute' => 'idCity',
                    'options' => [
                        'placeholder' => 'Выберите город ...',
                        'id' => 'idCity',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],

//            'idCity',
            //'idUser',
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
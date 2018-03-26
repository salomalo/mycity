<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\WorkCategory;
use common\models\City;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkResume */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('resume', 'My_resume');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-resume-index">
    <p>
        <?= Html::a(Yii::t('resume', 'Add_resume'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => '№',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'idCategory',
                'label' => 'Категория',
                'value' => function ($model) {
                    return $model->category->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(WorkCategory::find()->orderBy('title ASC')->all(), 'id', 'title'),
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
                'attribute' => 'title',
                'label' => 'Заголовок',
            ],
            [
                'attribute' => 'photoUrl',
                'label' => 'Картинка',
                'format' => 'html',
                'filter' =>false,
                'value' => function ($model) {
                     if($model->photoUrl){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'photoUrl', 100) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'idCity',
                'label' => 'Город',
                'value' => function ($model) {
                    return $model->city->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(City::find()->orderBy('title ASC')->all(),'id','title'),
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<?php

use common\models\Ads;
use yii\helpers\Html;
use yii\grid\GridView;
use common\extensions\nestedSelect;

/**
 * @var yii\web\View $this
 * @var common\models\search\Product $searchModel
 * @var integer $idCompany
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('ads', 'Ads');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('ads', 'Form_add'), ['create', 'idBusiness' => $idCompany], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view grid-view-border-top'],
        'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'title',
                'label'=>'Заголовок',
                'format' => 'raw',
                'value' => function (Ads $model) {
                    return Html::a($model->title, ['view', 'id' => (string)$model->_id]);
                }
            ],
            [
                'attribute' => 'Company, model',
                'value' => function ($model) {
                    return ($model->company) ? $model->company->title.' '.(($model->tovar)? $model->tovar->model : '') : ''.' '.$model->model;
                },
                'label'=>'Предприятие, Модель',
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'120px'],
                'filter' =>false,
                'value' => function ($model) {
                    if($model->image){
                        return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                    }
                    else return '';
                },
                'label'=>'Картинка',
            ],
            [
                'attribute' => 'idCategory',
                'value' => 'category.title',
                'options' => ['width'=>'350px'],
                'filter' => nestedSelect::widget([
                    'model' => $searchModel,
                    'attribute' => 'idCategory',
                    'options' => [
                        'placeholder' => 'Выберите категорию ...',
                        'id' => 'idCategory',
                        'multiple' =>false,
                    ],
                    'pluginOptions' => [
//                            'allowClear' => true,
                    ]
                ]),
                'label'=>'Категория',

            ],
            [
                'attribute'=>'price',
                'label'=>'Цена',
            ],
            [
                'attribute'=>'dateCreate',
                'label'=>'Время создания ',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Business;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DateTimePicker;
use common\models\Afisha;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Afisha */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, [
    'class' => 'btn bg-purple',
    'target' => '_blank',
    'style' => 'margin-left: 20px; margin-right: 15px;'
]);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'action'])?>

<div class="afisha-index">
    <p>
        <?= Html::a('Добавить фильм', ['create', 'idCompany'=>$idCompany, 'isFilm' => $isFilm], ['class' => 'btn btn-success']) ?>
        <?php // echo Html::a('Расписание фильмов', ['schedule-kino/index', 'idCompany' => $idCompany], ['class' => 'btn btn-success']) ?>
    </p>
    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width'=>'70px'],],

            //'id',
            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'title',
                'label' => 'Заголовок',               
            ],
            [
                'attribute' => 'image',
                'label' => 'Картинка',
                 'format' => 'html',
                'options' => ['width'=>'120px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 70) . ' " >';
                     }
                     else return '';
                },
            ], 
            'description:html',
            
            [
                'attribute' => 'genre',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return $model->genreNames($model->genre);
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(\common\models\KinoGenre::find()->all(),'id','title'),
//                    'data' => Afisha::$genre,
                    'attribute' => 'genre',
                    'options' => [
                            'placeholder' => 'Select a genre ...',
                            'id' => 'igenre',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),         
            ],  
              
//            [
//                'attribute' => 'idsCompany',
//                'label' => 'Преприятие',               
//                'value' => function ($model) {
//                    return $model->companyNames($model->idsCompany);
//                },
//                'filter'    => Select2::widget([
//                                'model' => $searchModel,
//                                'data' => ArrayHelper::map(Business::find()->where(['idUser'=>Yii::$app->user->id])->all(),'id','title'),
//                                'attribute' => 'idsCompany',
//                                'options' => [
//                                        'placeholder' => 'Выберите предприятие ...',
//                                        'id' => 'idsCompany',
//                                        //'multiple' => true,
//                                        ],
//                                'pluginOptions' => [
//                                        'allowClear' => true,
//                                ]
//                            ]),
//            ],
            //'description:html',
                           
//            [
//                'attribute' => 'dateStart',
//                'label' => 'Время начала',               
//            ],
//            [
//                'attribute' => 'dateEnd',
//                'label' => 'Время окончания',               
//            ],  
            
            
            

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}',
                'buttons' => [
                            'view' => function ($url, $model) {
                                return \yii\helpers\Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                    (new yii\grid\ActionColumn())->createUrl('afisha/view', $model, ['id' => $model->id, 'idCompany' => $this->context->idCompany], 1), [
                                        'title' => Yii::t('yii', 'view'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]);
                            },
                        ]
            ],
        ],
    ]); ?>

</div>

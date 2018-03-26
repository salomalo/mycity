<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\tree\TreeView;
use common\models\BusinessCategory;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\BusinessCategory $searchModel
 */

$this->title = 'Business Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="business-category-index">
<br>
<br>
<?=TreeView::widget([
    // single query fetch to render the tree
    'query'             => $query, 
    'headingOptions'    => ['label' => 'Категории предприятий'],
//    'rootOptions'       => ['label'=>'<span class="text-primary">Root</span>'],
    'fontAwesome'       => false,
    'isAdmin'           => false,                       // optional (toggle to enable admin mode)
    'displayValue'      => 1,                           // initial display value
    'softDelete'      => false,                        // normally not needed to change
    'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
]);
?>

<!--<? Html::a('Create Business Category', ['create'], ['class' => 'btn btn-success']) ?>-->

<!--    <h1><? Html::encode($this->title) ?></h1>
    <div style="padding-bottom: 20px;">
        Всего категорий в списке: < count($list)?>
    </div>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php // echo yii\helpers\BaseVarDumper::dump($list, 10, true); die(); ?>

<!--    <?$form = ActiveForm::begin([
        'id' => 'businesscategorylist',
        'method'=>'get',
        'action' => Url::to(['business-category/index']),
        ]); ?>-->
<!--        <?$form->field($model, 'pid')->widget(Select2::className(),[
        'data' => ArrayHelper::map($businessCategoryRoots,'id','title'),
        'options' => [
            'placeholder' => 'Выберите Категорию ...',
            'id' => 'productCategory',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
           'change' => "function() {  $('#businesscategorylist').submit(); }",
        ],
    ])->label("Корневая категория"); ?>-->
    <!--<? ActiveForm::end(); ?>-->
    <?php
//    $padding = [
//        1 => '2px 0px;',
//        2 => '2px 20px;',
//        3 => '2px 40px;',
//        4 => '2px 60px;',
//        5 => '2px 80px;',
//        6 => '2px 100px;',
//    ];
    ?>

    <!--<? foreach ($list as $item):?>-->
<!--        <div style="padding: <? $padding[$item['nested']]?>">
            <? $item['title'] ?>
            <? Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                    Yii::$app->urlManager->createUrl(['business-category/update', 'id' => $item['id'], 'nested' => ($item['nested'] > 1)? $item['nested'] : null]),
                    [
                        'title' => 'Update',
                        'data-pjax' => 0
                    ])?>
            <? Html::a('<span class="glyphicon glyphicon-trash"></span>',
                    Yii::$app->urlManager->createUrl(['business-category/delete', 'id' => $item['id']]),
                    [
                        'title' => 'Delete',
                        'data-confirm' => 'Are you sure you want to delete this item?'
                    ])?>
            <? if($item['nested'] < $maxNested):?>
                <? Html::a('<span class="glyphicon glyphicon-plus"></span>',
                    Yii::$app->urlManager->createUrl(['business-category/create', 'id' => $item['id'], 'nested' => ($item['nested'] > 1)? $item['nested'] : null]),
                    [
                        'title' => 'Добавить вложенную',
                        'data-pjax' => 0
                    ])?>
            <? endif;?>
        </div>-->
    <!--<? endforeach;?>-->

    <br>
    <p>
        <!--< Html::a('Create Business Category', ['create'], ['class' => 'btn btn-success']) ?>-->
    </p>

    <?php
//    echo GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'pid',
//            'title',
//            'image',
//
//            ['class' => 'yii\grid\ActionColumn'],
//        ],
//    ]);
    ?>

</div>

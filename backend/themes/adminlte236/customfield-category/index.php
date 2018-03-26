<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\ProductCustomfield;
use common\models\CustomfieldCategory;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CustomfieldCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customfield Categories';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="customfield-category-index">

   
    <?php $form = ActiveForm::begin(['id' => 'sel-customfields-category']); ?>

        <?php 
                echo $form->field(new ProductCustomfield(), 'idCategoryCustomfield')->widget(Select2::className(), [
        'data' => ArrayHelper::map(CustomfieldCategory::find()->orderBy('order ASC')->all(), 'id', 'title'),
        'options' => [
            'placeholder' => 'Select a category ...',
            'id' => 'idCategoryCustomfield',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
            "select2:select" => "function() { $('#sel-customfields-category').submit(); }",
        ]
    ]);
        ?>  
    
    <?php foreach($customfields as $item):?>
            <?php $id = $item['id'];?>
            <div style="border: 1px solid #000000;padding: 5px; display: table; margin-bottom: 5px;">
                <div style="width: 300px; display: table-cell;"><?=$item['customfieldCategory']?></div>
                <div style="width: 300px; display: table-cell;"><?=$item['title']?></div>
                
                <div style="width: 350px; display: table-cell;">
                <?php
                        foreach ($item['value'] as $item){
                            echo ('<div style="border-bottom: 1px dotted #ccc;">'.$item.'</div>');
                        }
                ?>
                   <!-- <a href="#" class="addcustomfieldValue">Добавить value</a> -->
                </div>
                <div style="width: 100px; display: table-cell;">
                    <a href="<?=\Yii::$app->urlManager->createUrl(['product-customfield/update', 'id' => $id])?>" title="Update" data-pjax="0" ><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="<?=\Yii::$app->urlManager->createUrl(['product-customfield/delete', 'id' => $id]);?>" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post" data-pjax="0" >
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </div>
            </div>

        <?php endforeach ?>
    
    <?php ActiveForm::end(); ?>

    <p>
        <?= Html::a('Create Customfield Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= Html::a('List Customfield Category', ['index', 'list'=> true], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
//         echo   GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn', 'options' => ['width'=>'70px'],],
//            
//            [
//                'attribute' => 'id',
//                'options' => ['width'=>'70px'],
//            ],
//            'title',
//
//            ['class' => 'yii\grid\ActionColumn', 'options' => ['width'=>'70px'],],
//        ],
//    ]); 
         ?>

</div>

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

    <p>
        <?= Html::a('Create Customfield Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
        GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width'=>'70px'],],
            
            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            'title',

            ['class' => 'yii\grid\ActionColumn', 'options' => ['width'=>'70px'],],
        ],
    ]); 
         ?>

</div>

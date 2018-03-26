<?php

use common\models\ViewCount;
use kartik\tree\TreeView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
//use common\extensions\SelectCategory\SelectCategory;
use common\models\BusinessCategory;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\widgets\Alert;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\BusinessCategory $searchModel
 */

$this->title = 'Категории предприятий';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="business-category-index">
   <br> 
   <br> 
   <?php if(Yii::$app->session->hasFlash('alert-success') || Yii::$app->session->hasFlash('alert-warning')):?>
        <?=
             Alert::widget([
            'type' => (Yii::$app->session->hasFlash('alert-success'))? Alert::TYPE_SUCCESS : Alert::TYPE_DANGER,
            'icon' => (Yii::$app->session->hasFlash('alert-success'))? 'glyphicon glyphicon-ok-sign' : 'glyphicon glyphicon-exclamation-sign',
            'body' => (Yii::$app->session->hasFlash('alert-success'))? Yii::$app->session->getFlash('alert-success') : Yii::$app->session->getFlash('alert-warning'),
            'delay' => 14000
        ]);
        ?>
   <?php endif;?>
    
<?php
//    TreeView::widget([
//    // single query fetch to render the tree
//    'query'             => $query, 
//    'headingOptions'    => ['label' => 'Категории предприятий'],
////    'rootOptions'       => ['label'=>'<span class="text-primary">Root</span>'],
//    'fontAwesome'       => false,
//    'isAdmin'           => false,                       // optional (toggle to enable admin mode)
//    'displayValue'      => 1,                           // initial display value
//    'softDelete'      => false,                        // normally not needed to change
//    'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
//]);
?>
    
     <p>
        <?= Html::a('Создать корневую категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p> 
    
    <?php $form = ActiveForm::begin([
        'id' => 'businesscategorylist',
        'method'=>'get',
        'action' => Url::to(['business-category/index']),
        ]); ?>
        <?= $form->field($model, 'pid')->widget(Select2::className(),[
        'data' => ArrayHelper::map(BusinessCategory::find()->orderBy(['title'=>'DESC'])->roots()->all(),'id','title'),
        'options' => [
            'placeholder' => 'Выберите Категорию ...',
            'id' => 'businessCategory',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
           'change' => "function() {  $('#businesscategorylist').submit(); }",
        ],    
    ])->label("Корневая категория товара"); ?>    
    <?php ActiveForm::end(); ?>

    <?php
    $views = ViewCount::getAllByType(ViewCount::CAT_BUSINESS_CATEGORY);
    $views = ArrayHelper::map($views, 'item_id', 'sum');
    foreach ($list as $item) {
        $pref = '';
        $style = "padding: 2px 0px;";

        if ($item['depth'] > 0) {
            $style = "padding: 2px " . ($item['depth'] * 10) . "px;";
            for ($i = 0; $i < $item['depth']; $i++) {
                $pref .= '-';
            }
        }
        $view = isset($views[$item['id']]) ? $views[$item['id']] : 0;
        echo $this->render('_list_item',
            ['style' => $style, 'pref' => $pref, 'item' => $item, 'view' => $view]);
    }
    ?>

</div>

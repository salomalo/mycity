<?php
/**
 * @var yii\web\View $this
 * @var common\models\search\ProductCategory $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var Product $model
 * @var $pid
 */

use common\models\Product;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\ProductCategory;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\widgets\Alert;

$this->title = 'Категории продуктов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-index">
    <br> 
    <br> 
    <?php if(Yii::$app->session->hasFlash('alert-success') || Yii::$app->session->hasFlash('alert-warning')):?>
         <?= Alert::widget([
             'type' => (Yii::$app->session->hasFlash('alert-success'))? Alert::TYPE_SUCCESS : Alert::TYPE_WARNING,
             'icon' => (Yii::$app->session->hasFlash('alert-success'))? 'glyphicon glyphicon-ok-sign' : 'glyphicon glyphicon-exclamation-sign',
             'body' => (Yii::$app->session->hasFlash('alert-success'))? Yii::$app->session->getFlash('alert-success') : Yii::$app->session->getFlash('alert-warning'),
             'delay' => 3000
         ]) ?>
    <?php endif;?>

    <p><?= Html::a('Создать корневую категорию', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php $form = ActiveForm::begin(['id' => 'productcategorylist', 'action' => Url::to(['product-category/index'])]) ?>

    <?= $form->field($model, 'pid')->widget(Select2::className(), [
        'data' => ArrayHelper::map(ProductCategory::find()->orderBy(['title'=>'DESC'])->roots()->all(),'id','title'),
        'options' => ['placeholder' => 'Выберите Категорию ...'],
        'pluginOptions' => ['allowClear' => true],
        'pluginEvents' => ['change' => "function() { $('#productcategorylist').submit(); }"],
    ])->label('Корневая категория товара'); ?>

    <?php ActiveForm::end(); ?>
    
    <?php foreach ($list as $item) : ?>
        <?php $pref = '';
        if ($item['depth'] > 0) {
            $style = 'style="padding: 2px '.($item['depth']*10).'px;"';
            for($i = 0; $i < $item['depth']; $i++){
                $pref .= '-';
            }
        } else {
            $style = 'style="padding: 2px 0px;"';
        }
        ?>
        
        <div <?= $style ?> > <?= $pref ?> <?= $item['title'] ?>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['product-category/update', 'id' => $item['id']],
                ['title' => 'Update' , 'data-pjax' => '0']
            ) ?>
            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['product-category/delete', 'id' => $item['id']],
                ['title' => 'Delete' , 'data-confirm' => 'Are you sure you want to delete this item?']
            ) ?>
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', 
                ['product-category/add', 'id' => $item['id']],
                ['title' => 'Добавить вложенную' , 'data-pjax' => '0']
            ) ?>
            <?= Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', 
                ['product-category/up', 'id' => $item['id'], 'pid' => $pid],
                ['title' => 'Переместить вверх' , 'data-pjax' => '0']
            ) ?>
            <?= Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', 
                ['product-category/down', 'id' => $item['id'], 'pid' => $pid],
                ['title' => 'Переместить вниз' , 'data-pjax' => '0']
            ) ?>
            <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', 
                ['product-category/left', 'id' => $item['id'], 'pid' => $pid],
                ['title' => 'Переместить влево' , 'data-pjax' => '0']
            ) ?>
            <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', 
                ['product-category/right', 'id' => $item['id'], 'pid' => $pid],
                ['title' => 'Переместить вправо' , 'data-pjax' => '0']
            ) ?>
        </div>
    <?php endforeach;?>
</div>
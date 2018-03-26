<?php

use common\models\ProductCategory;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\search\Product $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;

/** @var ProductCategory[] $root_cats */
$root_cats = ProductCategory::find()->roots()->all();

$categories = [];

foreach ($root_cats as $root_cat) {
    $categories[$root_cat->id] = $root_cat->title;

    /** @var ProductCategory[] $children_cats */
    $children_cats = $root_cat->children(1)->all();

    if (!$children_cats) {
        continue;
    }

    foreach ($children_cats as $cat) {
        $categories[$cat->id] = "-{$cat->title}";

        /** @var ProductCategory[] $other_cats */
        $other_cats = $cat->children()->select(['id', 'title'])->all();

        if (!$other_cats) {
            continue;
        }

        foreach ($other_cats as $other_cat) {
            $categories[$other_cat->id] = "--{$other_cat->title}";
        }
    }
}
?>
<div class="product-index">

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => '_id',
                'options' => ['width' => '250px'],
            ],
            [
                'attribute' => 'title',
                'value' => function ($model) {
//                    return ($model->company) ? $model->company->title : ''.' '.$model->model;
                    return ($model->title) ? $model->title : '';
                },
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width' => '120px'],
                'filter' => false,
                'value' => function ($model) {
                    if ($model->image) {
                        return '<img  width="100"  src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                    } else return '';
                },
            ],
            [
                'attribute' => 'idCategory',
                'value' => 'category.title',
                'options' => ['width' => '350px'],

                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'idCategory',
                    'data' => $categories,
                    'options' => [
                        'multiple' => true,
                        'placeholder' => 'Выберите категорию',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

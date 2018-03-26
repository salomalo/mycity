<?php

use common\models\ProductCompany;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\search\ProductCompany $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Product Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-company-index">


    <p>
        <?= Html::a('Create Product Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'filter' => false,
                'value' => function (ProductCompany $model) {
                    return $model->image ? Html::img(Yii::$app->files->getUrl($model, 'image', 100)) : '';
                },
            ],

            [
                'label' => 'Категории',
                'filter' => false,
                'value' => function (ProductCompany $model) {
                    return implode(', ', ArrayHelper::getColumn($model->getCategories()->select('title')->all(), 'title'));
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

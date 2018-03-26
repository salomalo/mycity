<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\ProductCustomfield;
use common\models\ProductCompany;

/**
 * @var yii\web\View $this
 * @var common\models\Product $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
    $company = ProductCompany::find()->select('title')->where(['id' => $model->idCompany])->asArray()->one();

    $attributes = [
        '_id',
        'title',
        [
            'attribute' => 'seo_title',
            'value' => $model->seo_title ? $model->seo_title : '',
        ],
        'seo_description',
        'seo_keywords',
        [
            'attribute' => 'sitemap_en',
            'value' => $model->sitemap_en ? 'да' : 'нет',
        ],

        [
            'attribute' => 'sitemap_priority',
            'value' => $model->sitemap_priority ? $model->sitemap_priority : '',
        ],
        [
            'attribute' => 'sitemap_changefreq',
            'value' => $model->sitemap_changefreq ? $model->sitemap_changefreq : '',
        ],
        'url',
        'image',
        'video',
        'category.title',
        [
            'label' => 'idCompany',
            'value' => $company['title'],
        ],
        'model',
        'description:html',
    ];

    if($model->idCategory){
        foreach(ProductCustomfield::findAll(['idCategory' => $model->idCategory]) as $cf){
            if(isset($model[$cf->alias])){
                $attributes = array_merge( $attributes, [['label' => $cf->title, 'value'=>$model[$cf->alias]]]);
            }
        }  
    }
    ?>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>

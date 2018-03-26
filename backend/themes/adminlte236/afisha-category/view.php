<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AfishaCategory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Afisha Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afisha-category-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create', 'isFilm' => ($model->isFilm)? true : null ], 
                              ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index', 'isFilm' => ($model->isFilm)? true : null ], 
                            ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'seo_title', 
                'value' => ($model->seo_title)? $model->seo_title : '',
            ],
            'seo_description',
            'seo_keywords', 
            [
                'attribute' => 'sitemap_en', 
                'value' => ($model->sitemap_en)? 'да' : 'нет',
            ],
            
            [
                'attribute' => 'sitemap_priority', 
                'value' => ($model->sitemap_priority)? $model->sitemap_priority : '',
            ],
            [
                'attribute' => 'sitemap_changefreq', 
                'value' => ($model->sitemap_changefreq)? $model->sitemap_changefreq : '',
            ],
            'url',
            [
                'attribute' => 'pid',
                'value' => ($model->parent)? $model->parent->title : '',
            ],
            'image',
            'order',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Afishas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afisha-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create', 'isFilm'=>true], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index', 'isFilm'=>true], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'url',
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
            [
                'attribute' => 'genre',
                'type' => 'raw',
                'value' => $model->genreNames($model->genre),
            ],
            [
                'attribute' => 'idCategory',
                'value' => $model->category->title,
            ],
            'year',
            'country',
            'director',
            'actors',
            'budget',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => ($model->image)? Html::img(\Yii::$app->files->getUrl($model, 'image', 70)) : '',
            ],
            'trailer',
            'description:html',
            'fullText:html',
            
            'rating',
            'tags',
            'order'
        ],
    ]) ?>

</div>

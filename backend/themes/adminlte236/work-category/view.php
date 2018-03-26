<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WorkCategory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Work Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
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
                'attribute' => 'seo_title_resume', 
                'value' => ($model->seo_title_resume)? $model->seo_title_resume : '',
            ],
            'seo_description_resume',
            'seo_keywords_resume',   
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
        ],
    ]) ?>

</div>

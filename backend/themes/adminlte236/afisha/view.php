<?php

use common\models\Afisha;
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
            'image',
            [
                'attribute' => 'idCategory',
                'value' => $model->category->title,
            ],
            [
                'attribute' => 'idsCompany',
                'value' => $model->companyNames($model->idsCompany),
            ],
            'description:html',
            'dateStart',
            'dateEnd',
            [
                'attribute' => 'times',
                'value' => $model->getTimes($model->times),
            ],
            'price',
            'rating',
            'tags',
            [
                'attribute' => 'repeat',
                'value' => isset(Afisha::$repeat_type[$model->repeat]) ?
                    Afisha::$repeat_type[$model->repeat] : $model->repeat,
            ],
            [
                'attribute' => 'repeatDays',
                'value' => $model->getDays($model->repeatDays)
            ]
        ],
    ]) ?>

</div>

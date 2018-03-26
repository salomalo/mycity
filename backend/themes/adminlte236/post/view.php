<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create', 'id' => $model->idCategory], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'status',
                'value' => isset(Post::$types[$model->status]) ? Post::$types[$model->status] : $model->status,
            ],
            'city.title',
            'business.title:text:Предприятие',
            [
                'attribute' => 'allCity',
                'value' => $model->allCity ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'onlyMain',
                'value' => $model->onlyMain ? 'Да' : 'Нет',
            ],
            'user.username',
            'category.title',
            'title',
            'seo_title',
            'seo_description',
            'seo_keywords',  
             [
                'attribute' => 'sitemap_en', 
                'value' => $model->sitemap_en ? 'да' : 'нет',
            ],
            'sitemap_priority',
            'sitemap_changefreq',
            'url',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => $model->image ? Html::img(\Yii::$app->files->getUrl($model, 'image', 90)) : '',
            ],
            [
                'attribute' => 'video',
                'value' => $model->getVideos($model->video),
            ],
            'shortText:html',
            'fullText:html',
            'address',
            'lat',
            'lon',
            'dateCreate:datetime',
            'tags',
        ],
    ]) ?>

</div>

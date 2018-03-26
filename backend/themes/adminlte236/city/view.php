<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'idRegion',
                'type' => 'raw',
                'value' => isset($model->region->title) ? $model->region->title : '',
            ],
            [
                'attribute' => 'title',
                'format' => 'text',
                'value' => $model->title,
            ],
            [
                'attribute' => 'title_ge',
                'format' => 'text',
                'value' => $model->title_ge,
            ],
            'code:text',
            'subdomain:text',
            [
                'attribute' => 'titleAbout',
                'format' => 'text',
                'value' => $model->titleAbout,
            ],
            [
                'attribute' => 'about',
                'format' => 'html',
                'value' =>  $model->about,
            ],
            [
                'attribute' => 'seo_title',
                'format' => 'text',
                'value' => $model->seo_title,
            ],
            [
                'attribute' => 'seo_description',
                'format' => 'text',
                'value' => $model->seo_description,
            ],
            [
                'attribute' => 'seo_keywords',
                'format' => 'text',
                'value' => $model->seo_keywords,
            ],
            [
                'attribute' => 'google_analytic',
                'format' => 'text',
                'value' => $model->google_analytic,
            ],
            'vk_public_id',
            'vk_public_admin_id',
            [
                'attribute' => 'vk_public_admin_token',
                'format' => 'html',
                'value' => empty($model->vk_public_admin_token) ? '<span class="glyphicon glyphicon-remove"></span>' : '<span class="glyphicon glyphicon-ok"></span>',
            ],
        ],
    ]) ?>

</div>

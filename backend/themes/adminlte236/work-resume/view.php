<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Work Resumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-resume-view">

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
        <?= Html::a('Create', ['create', 'id' => $model->idCategory], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'idCategory',
                'value' => $model->category->title,
            ],
            [
                'attribute' => 'idUser',
                'value' => $model->user->username,
            ],
            [
                'attribute' => 'idCity',
                'value' => $model->city->title,
            ],
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
            'description:html',
            'name',
            'photoUrl',
            'year',
            'experience',
            [
                'attribute' => 'education',
                'value' => ($model->education)? $model->educationList[$model->education] : '',
            ],
            'male',
            'salary',
            'isFullDay',
            'isOffice',
            'phone',
            'email:email',
            'skype',
            'dateCreate',
        ],
    ]) ?>

</div>

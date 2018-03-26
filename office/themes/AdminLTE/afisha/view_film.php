<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */
/* @var $business common\models\Business */

$this->title = $business->title;
$this->params['breadcrumbs'][] = $this->title;
if ($business){
    $link = Url::to(['/afisha/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>
<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'afisha'])?>

<div class="afisha-view">

    <h3><?= $model->title ?></h3>

    <p>
        <?= Html::a('Create', ['create', 'idCompany'=>$idCompany, 'isFilm'=>true], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['afisha/list-films', 'idCompany'=>$idCompany, 'isFilm'=>true], ['class' => 'btn btn-info']) ?>
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
            'image',
            'trailer',
            'description:html',
            'fullText:html',
            
            'rating',
        ],
    ]) ?>

</div>

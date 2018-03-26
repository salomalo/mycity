<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Post
 * @var $business \common\models\Business
 */

use common\models\Comment;
use common\models\File;
use yii\helpers\Url;

$countComment = Comment::find()->where(['pid' => $model->id, 'type' => File::TYPE_POST])->count();
$alias = "{$business->id}-{$business->url}";
$url = Url::to(['/business/' . $alias . '/blog/' . "{$model->id}-{$model->url}"]);

$date = new DateTime($model->dateCreate);
?>

<div id="post-<?= $model->id ?>" class="post post-masonry post-<?= $model->id ?> type-post status-publish format-standard has-post-thumbnail hentry category-gastronomy tag-fast-food tag-image tag-post tag-video" >

    <div class="post-masonry-content">

        <!--        -->
        <div class="post-image" style="background-image: url('<?= Yii::$app->files->getUrl($model, 'image') ?>');">
            <a href="<?= $url?>"></a>
        </div><!-- /.listing-column-image-->
        <!--        -->
        <div class="post-meta-categories" style="position: absolute;">
        </div><!-- /.listing-column-content -->

        <div class="post-content" style="padding-top: 30px;">
            <h3><a href="<?= $url?>"><?= $model->title ?></a></h3>
            <p><?= $model->shortText ?></p>
        </div><!-- /.listing-column-title -->

        <div class="post-meta">
            <div class="post-meta-date"><i class="fa fa-calendar"></i> <?= $date->format('d/m/Y')  ?></div><!-- /.post-meta-date -->

            <div class="post-meta-comments"><i class="fa fa-comments"></i> <?= $countComment ?> Коментария</div><!-- /.post-meta-comments -->
        </div>
    </div>
</div><!-- /.post -->



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

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="blogBox style2">
        <div class="blogDesc">
            <span class="blog-title"><a href="<?= $url?>"><?= $model->title ?></a></span>
            <?php if(isset($model->category->title)) : ?>
                <p class="blogInfo"><?= $date->format('d.m.Y H:i')  ?>, <?= isset($model->user->username) ? $model->user->username : '' ?>, в <?= $model->category->title ?></p>
            <?php else: ?>
                <p class="blogInfo"><?= $date->format('d.m.Y H:i')  ?>, <?= isset($model->user->username) ? $model->user->username : '' ?></p>
            <?php endif; ?>
        </div><!-- ( BLOG DESCRIPTION END ) -->
        <div class="blogImage">
            <div class="zoom">
                <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" width="220" height="180" alt="">
                <div class="blogDate">
                    <i><?= $date->format('M') ?></i>
                    <hr>
                    <span><?= $date->format('d') ?></span>
                    <hr>
                    <i><?= $date->format('Y')  ?></i>
                </div><!-- ( BLOG DATE END ) -->
            </div><!-- ( HOVER STYLE END ) -->
        </div><!-- ( BLOG IMAGE END ) -->
        <div class="blogDesc">
            <?= $model->shortText ?>
            <a href="<?= $url?>" class="more2">Узнать больше...</a>
        </div><!-- ( BLOG DESCRIPTION END ) -->
    </div><!-- ( BLOG BOX END ) -->
</div>

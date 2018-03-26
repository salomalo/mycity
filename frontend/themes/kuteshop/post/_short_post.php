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
?>

<li class="post-item">
    <article class="entry">
        <div class="row">
            <div class="col-sm-5">
                <div class="entry-thumb image-hover2">
                    <a href="<?= $url?>">
                        <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" alt="Blog">
                    </a>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="entry-ci">
                    <h3 class="entry-title"><a href="<?= $url ?>"><?= $model->title ?></a></h3>
                    <div class="entry-meta-data">
                        <span class="author">
                            <i class="fa fa-user"></i>
                            Написал: <?= isset($model->user->username) ? $model->user->username : '' ?></span>
                        <?php if(isset($model->category->title)) : ?>
                            <span class="cat">
                                <i class="fa fa-folder-o"></i>
                                <?= $model->category->title ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($countComment) : ?>
                            <span class="comment-count">
                                <i class="fa fa-comment-o"></i><?= $countComment ?>
                            </span>
                        <?php endif; ?>
                        <span class="date"><i class="fa fa-calendar"></i> <?= $model->dateCreate ?></span>
                    </div>
                    <div class="entry-excerpt">
                        <?= $model->shortText ?>
                    </div>
                    <div class="entry-more">
                        <a href="<?= $url?>">Узнать больше</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
</li>

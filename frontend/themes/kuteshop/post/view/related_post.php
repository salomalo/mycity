<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Post
 * @var $business \common\models\Business
 */

use common\models\Comment;
use common\models\File;
use common\models\Post;
use yii\helpers\Url;

$posts = Post::find()
    ->where(['business_id' => $model->business_id])
    ->andWhere(['!=', 'id', $model->id])
    ->limit(4)
    ->orderBy(['id' => SORT_DESC])
    ->all();
?>
<?php if ($posts) : ?>
<!-- Related Posts -->
<div class="single-box">
    <h2>Похожие новости</h2>
    <ul
        class="related-posts owl-carousel"
        data-dots="false"
        data-loop="true"
        data-nav = "true"
        data-margin = "30"
        data-autoplayTimeout="1000"
        data-autoplayHoverPause = "true"
        data-responsive='{"0":{"items":1},"600":{"items":2},"1000":{"items":3}}'>
        <?php foreach ($posts as $model) : ?>
            <?php
            $alias = "{$business->id}-{$business->url}";
            $url = Url::to(['/business/' . $alias . '/blog/' . "{$model->id}-{$model->url}"]);

            $date = date_create($model->dateCreate);
            $countComment = Comment::find()->where(['pid' => $model->id, 'type' => File::TYPE_POST])->count();
            ?>
            <li class="post-item">
                <article class="entry">
                    <div class="entry-thumb image-hover2">
                        <a href="#">
                            <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" alt="Blog">
                        </a>
                    </div>
                    <div class="entry-ci">
                        <h3 class="entry-title"><a href="<?= $url ?>"><?= $model->title ?></a></h3>
                        <div class="entry-meta-data">
                            <?php if ($countComment) : ?>
                                <span class="comment-count">
                                        <i class="fa fa-comment-o"></i><?= $countComment ?>
                                    </span>
                            <?php endif; ?>
                            <span class="date">
                                <i class="fa fa-calendar"></i> <?= date_format($date, 'Y-m-d') ?>
                            </span>
                        </div>
                        <div class="entry-more">
                            <a href="<?= $url ?>">Узнать больше</a>
                        </div>
                    </div>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<!-- ./Related Posts -->
<?php endif; ?>
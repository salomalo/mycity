<?php
/**
 * @var $this \yii\web\View
 * @var $business \common\models\Business
 */
use common\models\Comment;
use common\models\File;
use yii\helpers\Url;

/** @var \common\models\Post[] $posts */
$posts = \common\models\Post::find()
    ->groupBy('post.id')
    ->orderBy(['post.id' => SORT_DESC])
    ->joinWith('comment')
    ->select(['post.*', 'comment_count' => 'COUNT(comment.id)'])
    ->joinWith(['countView'])
    ->addSelect(['countViews' => 'COALESCE(count_views.count, 0)'])
    ->addGroupBy(['countViews'])
    ->orderBy(['countViews' => SORT_DESC])
    ->where(['business_id' => $business->id])
    ->limit(3)
    ->all();
?>
<?php if ($posts) : ?>
    <!-- Block  Popular Posts-->
    <div class="block-sidebar block-PopularPosts">
        <div class="block-title">
            <strong>Популярные новости</strong>
        </div>
        <div class="block-content">
            <ul class="blog-list-sidebar clearfix">
                <?php foreach ($posts as $model) : ?>
                    <?php
                    $alias = "{$business->id}-{$business->url}";
                    $url = Url::to(['/business/' . $alias . '/blog/' . "{$model->id}-{$model->url}"]);

                    $date = date_create($model->dateCreate);
                    $countComment = Comment::find()->where(['pid' => $model->id, 'type' => File::TYPE_POST])->count();
                    ?>
                    <li>
                        <div class="post-thumb">
                            <a href="<?= $url?>"><img alt="Blog" src="<?= Yii::$app->files->getUrl($model, 'image') ?>"></a>
                        </div>
                        <div class="post-info">
                            <h5 class="entry_title"><a href="<?= $url?>"><?= $model->title ?></a></h5>
                            <div class="post-meta">
                                <span class="date"><i class="fa fa-calendar"></i> <?= date_format($date, 'Y-m-d') ?></span>
                                <?php if ($countComment) : ?>
                                    <span class="comment-count">
                                        <i class="fa fa-comment-o"></i><?= $countComment ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div><!-- Block  Popular Posts-->
<?php endif; ?>
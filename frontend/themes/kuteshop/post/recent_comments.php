<?php
/**
 * @var $this \yii\web\View
 * @var $business \common\models\Business
 */

use common\models\Comment;
use common\models\Post;
use yii\helpers\Url;

/** @var Comment[] $comments */
$comments = Comment::find()
    ->select('comment.*')
    ->leftJoin('post', 'post."id" = comment."pid"')
    ->where(['post."business_id"' => $business->id])
    ->limit(3)
    ->orderBy(['id' => SORT_DESC])
    ->all();

?>
<?php if ($comments) : ?>
    <!-- Block RecentComments-->
    <div class="block-sidebar block-sidebar-RecentComments">
        <div class="block-title">
            <strong>Recent Comments</strong>
        </div>
        <div class="block-content">
            <ul class="recent-comment-list">
                <?php foreach ($comments as $comment) : ?>
                    <?php
                    $post = Post::findOne(['id' => (int)$comment->pid]);

                    $alias = "{$business->id}-{$business->url}";
                    $url = Url::to(['/business/' . $alias . '/blog/' . "{$post->id}-{$post->url}"]);
                    ?>
                    <li>
                        <h5><a href="<?= $url ?>"><?= $post->title ?></a></h5>
                        <div class="comment">
                            <?= $comment->text ?>
                        </div>
                        <div class="author">Написал : <?= $comment->user->username ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div><!-- Block  RecentComments-->
<?php endif; ?>

<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Post
 * @var $business \common\models\Business
 */

use common\models\Lang;
use yii\helpers\Url;
use common\models\Comment;
use common\models\File;
use yii\widgets\Breadcrumbs;

$countComment = Comment::find()->where(['pid' => $model->id, 'type' => File::TYPE_POST])->count();
$alias = "{$business->id}-{$business->url}";
$url = Url::to(['/business/' . $alias . '/blog/' . "{$model->id}-{$model->url}"]);

$date = new DateTime($model->dateCreate);
?>

<div class="main">


    <div class="main-inner">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div id="primary">

                        <div class="document-title">
                            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                                <?= Breadcrumbs::widget([
                                    'tag' => 'ol',
                                    'options' => ['class' => 'breadcrumb'],
                                    'homeLink' => false,
                                    'links' => $breadcrumbs,
                                ]); ?>
                            <?php endif; ?>
                            <h1>
                                <?= $model->title?>
                            </h1>

                        </div><!-- /.document-title -->


                        <div class="post post-detail post-350 type-post status-publish format-standard has-post-thumbnail hentry category-gastronomy tag-fast-food tag-image tag-post tag-video">
                            <img width="1024" height="600"
                                 src="<?= Yii::$app->files->getUrl($model, 'image') ?>"
                                 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt=""
                                 srcset="<?= Yii::$app->files->getUrl($model, 'image') ?> 1024w, <?= Yii::$app->files->getUrl($model, 'image') ?> 600w"
                                 sizes="(max-width: 1024px) 100vw, 1024px">
                            <div class="post-meta clearfix">
                                <div class="post-meta-author">By <?= isset($model->user->username) ? $model->user->username : '' ?></div><!-- /.post-meta-author -->
                                <div class="post-meta-date"><?= $date->format('d/m/Y')  ?></div><!-- /.post-meta-date -->
                                <div class="post-meta-comments"><i class="fa fa-comments"></i> <?= $countComment ?> Комментариев</div><!-- /.post-meta-comments -->
                            </div><!-- /.post-meta -->

                            <div class="post-content">
                                <?= $model->fullText ?>
                            </div><!-- /.post-content -->

                            <div id="comments" class="comments-area">

                                <div id="respond" class="comment-respond">
                                    <h3 id="reply-title" class="comment-reply-title">Оставь комментарий <small><a rel="nofollow" id="cancel-comment-reply-link" href="/vestibulum-aliquet-aliq/#respond" style="display:none;">Cancel reply</a></small></h3>
<!--                                    <form action="http://superlist.byaviators.com/wp-comments-post.php" method="post" id="commentform" class="comment-form">-->
<!--                                        <p class="comment-notes"><span id="email-notes">Your email address will not be published.</span> Required fields are marked <span class="required">*</span></p><div class="form-group"><label for="comment">Comment <span class="required">*</span></label><textarea id="comment" name="comment" class="form-control" cols="45" rows="5" aria-required="true"></textarea></div>-->
<!--                                        <p class="form-allowed-tags">You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:  <code>&lt;a href="" title=""&gt; &lt;abbr title=""&gt; &lt;acronym title=""&gt; &lt;b&gt; &lt;blockquote cite=""&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=""&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=""&gt; &lt;s&gt; &lt;strike&gt; &lt;strong&gt; </code></p><div class="row"><div class="form-group col-sm-4"><label for="comment-author">Name <span class="required">*</span></label><input id="comment-author" type="text" required="required" class="form-control" name="author"></div>-->
<!--                                            <div class="form-group col-sm-4"><label for="comment-email">Email <span class="required">*</span></label><input id="comment-email" type="email" required="required" class="form-control" name="email"></div>-->
<!--                                            <div class="form-group col-sm-4"><label for="comment-website">Website</label><input id="comment-website" type="url" class="form-control" name="website"></div></div>-->
<!--                                        <p class="form-submit"><input name="submit" type="submit" id="submit" class="btn btn-primary btn-block" value="Post Comment"> <input type="hidden" name="comment_post_ID" value="350" id="comment_post_ID">-->
<!--                                            <input type="hidden" name="comment_parent" id="comment_parent" value="0">-->
<!--                                        </p>			-->
<!--                                    </form>-->
                                </div><!-- #respond -->
                            </div>

                        </div><!-- /.post -->

                    </div><!-- #primary -->
                </div><!-- /.col-* -->

            </div><!-- /.row -->

    </div><!-- /.main-inner -->
</div>

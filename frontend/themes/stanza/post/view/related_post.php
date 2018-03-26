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
    ->limit(3)
    ->orderBy(['id' => SORT_DESC])
    ->all();
?>

<?php if ($posts) : ?>
    <div class="relatedPost">
        <h4 class="borderBottom">Похожие новости</h4>
        <div class="row">
            <?php foreach ($posts as $model) : ?>
                <?php
                $countComment = Comment::find()->where(['pid' => $model->id, 'type' => File::TYPE_POST])->count();
                $alias = "{$business->id}-{$business->url}";
                $url = Url::to(['/business/' . $alias . '/blog/' . "{$model->id}-{$model->url}"]);

                $date = new DateTime($model->dateCreate);
                ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="blogBox style2">
                        <div class="blogDesc">
                            <span class="blog-title"><a href="<?= $url ?>"><?= $model->title ?></a></span>
                            <?php if(isset($model->category->title)) : ?>
                                <p class="blogInfo"><?= $date->format('d.m.Y H:i')  ?>, <?= isset($model->user->username) ? $model->user->username : '' ?>, в <?= $model->category->title ?> | <a href="<?= $url ?>"><?= $countComment ?> Коментариев</a></p>
                            <?php else: ?>
                                <p class="blogInfo"><?= $date->format('d.m.Y H:i')  ?>, <?= isset($model->user->username) ? $model->user->username : '' ?> | <a href="<?= $url ?>"><?= $countComment ?> Коментариев</a></p>
                            <?php endif; ?>
                        </div><!-- ( BLOG DESCRIPTION END ) -->
                        <div class="blogImage">
                            <div class="zoom">
                                <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" width="323" height="245" alt="">
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
                            <a href="<?= $url ?>" class="more2">Узнать больше...</a>
                        </div><!-- ( BLOG DESCRIPTION END ) -->
                    </div><!-- ( BLOG BOX END ) -->
                </div>
            <?php endforeach; ?>
        </div>
    </div><!-- ( RELATED POST END ) -->
<?php endif; ?>

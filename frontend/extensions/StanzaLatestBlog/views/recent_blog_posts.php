<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Post[]
 */
use yii\helpers\Url;

?>
<div class="col-md-3 col-sm-6">
    <div class="widget_container">
        <h4>Недавние новости</h4>
        <div class="recent-posts clearfix">
            <ul>
                <?php foreach ($models as $post) : ?>
                    <li class="clearfix">
                        <a href="<?= Url::to($post->getRoute()) ?>">
                            <div class="blog-thumb">
                                <img src="<?= Yii::$app->files->getUrl($post, 'image')?>" width="32" height="32" alt="">
                            </div>
                            <div class="blog-desc">
                                <h5 class="blog-title"><?= $post->title ?></h5>
                                <p class="desc" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                    <?= strip_tags($post->shortText, '<br>') ?>
                                </p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div><!-- ( RECENT POSTS END ) -->
    </div>
</div>

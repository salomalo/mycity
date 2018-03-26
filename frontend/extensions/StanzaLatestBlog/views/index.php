<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Post[]
 * @var $business \common\models\Business
 */
use yii\helpers\Html;

?>

<div class="stripe">
    <div class="container">
        <h3 class="dashStyle">Последние новости</h3>
        <div class="blogRow row">
            <?php foreach ($models as $post) : ?>
                <?php
                $date = new DateTime($post->dateCreate);
                ?>
                <div class="col-md-3 col-sm-6 col-xs-12 col-xs-12-ls">
                    <div class="blogBox">
                        <div class="blogImage zoom">
                            <img src="<?= Yii::$app->files->getUrl($post, 'image')?>" width="263" height="240" alt="">
                            <div class="blogDate">
                                <i><?= $date->format('M') ?></i>
                                <hr>
                                <span><?= $date->format('d') ?></span>
                                <hr>
                                <i><?= $date->format('Y')  ?></i>
                            </div><!-- ( BLOG DATE END ) -->
                        </div><!-- ( BLOG IMAGE END ) -->
                        <div class="blogDesc">
                            <span class="blog-title"><?= Html::a($post->title, $post->getRoute()) ?></span>
                            <p class="blogInfo"><?= $date->format('d.m.Y, H:i')  ?> by <a href=""><?= $business->user->username ?></a> in <a href=""><?= $business->title ?></a></p>
                            <p><?= $post->shortText ?></p>
                            <?= Html::a('Читать больше...', $post->getRoute()) ?>
                        </div><!-- ( BLOG DESCRIPTION END ) -->
                    </div>
                </div>
            <?php endforeach; ?>
        </div><!-- ( BLOG ROW END ) -->
    </div>
</div><!-- ( STRIPE END ) -->

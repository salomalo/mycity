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

$date = new DateTime($model->dateCreate);
?>

<div class="innerHeading bg_f1f1f1 innerHeading-border">
        <div class="container text-center">
            <h1 class="marginBottomNone"><?= $model->title ?></h1>
            <div class="breadcrumb">
                <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumb'],
                        'homeLink' => false,
                        'links' => $breadcrumbs,
                    ]); ?>
                <?php endif; ?>
            </div><!-- ( BREAD CRUMB END ) -->
        </div>
    </div><!-- ( INNER HEADING END ) -->

<div id="content" class="blogPage blogDetail style3">
        <div class="stripe-1">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 blogContent">
                        <div class="blogBox clearfix">
                            <h3 class="fontsize_30"><?= $model->title ?></h3>
                            <div class="blogImage">
                                <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" width="1120" height="764" alt="">
                                <div class="blogDate">
                                    <i><?= $date->format('M') ?></i>
                                    <hr>
                                    <span><?= $date->format('d') ?></span>
                                    <hr>
                                    <i><?= $date->format('Y')  ?></i>
                                </div><!-- ( BLOG DATE END ) -->
                            </div><!-- ( BLOG IMAGE END ) -->
                            <div class="blogDesc">
                                <?= $model->fullText ?>

                                <?= $this->render('view/gallery', ['model' => $model]) ?>


                                <div class="blogListFooter">
                                    <p>
                                        <span class="listAuthor"><i class="fa fa-clock-o"></i>By <?= isset($model->user->username) ? $model->user->username : '' ?></span>
                                        <span class="listTime"><?= $model->dateCreate ?></span>
                                        <span class="listComme"><a href="#">0 Comments</a></span>
                                    </p>
                                    <div class="shareField">
                                        <span class='st_facebook_large' displayText='Facebook'></span>
                                        <span class='st_twitter_large' displayText='Tweet'></span>
                                        <span class='st_email_large' displayText='Email'></span>
                                        <span class='st_sharethis_large' displayText='ShareThis'></span>
                                    </div><!-- ( SHARE FIELD END ) -->
                                </div><!-- ( BLOG LIST FOOTER END ) -->
                            </div><!-- ( BLOG DESCRIPTION END ) -->
                        </div><!-- ( BLOG BOX END ) -->

                        <?= $this->render('view/related_post', ['model' => $model, 'business' => $business]) ?>
                    </div>
                </div>
            </div>
        </div><!-- ( STRIPE END ) -->
    </div><!-- ( CONTENT END ) -->


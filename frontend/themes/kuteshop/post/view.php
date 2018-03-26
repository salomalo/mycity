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
?>
<!-- MAIN -->
<main class="site-main">

        <div class="columns container">
            <!-- Block  Breadcrumb-->

            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                <?= Breadcrumbs::widget([
                    'tag' => 'ol',
                    'options' => ['class' => 'breadcrumb'],
                    'homeLink' => false,
                    'links' => $breadcrumbs,
                ]); ?>
            <?php endif; ?>

            <div class="row">

                <!-- Main Content -->
                <div class="col-md-9 col-md-push-3  col-main ">
                    <h1 class="page-heading">
                        <span class="page-heading-title2"><?= $model->title ?></span>
                    </h1>
                    <article class="entry-detail">
                        <div class="entry-meta-data">
                                <span class="author">
                                <i class="fa fa-user"></i>
                                Написал : <?= isset($model->user->username) ? $model->user->username : '' ?></span>
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
                        <div class="entry-photo">
                            <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" alt="Blog">
                        </div>
                        <div class="content-text clearfix">
                            <?= $model->fullText ?>
                        </div>
<!--                        <div class="entry-tags">-->
<!--                            <span>Tags:</span>-->
<!--                            <a href="#">beauty,</a>-->
<!--                            <a href="#">medicine,</a>-->
<!--                            <a href="#">health</a>-->
<!--                        </div>-->
                    </article>

                    <?= $this->render('view/related_post', ['model' => $model, 'business' => $business]) ?>

                    <?= $this->render('view/comments', ['model' => $model]) ?>
                </div><!-- Main Content -->

                <!-- Sidebar -->
                <div class=" col-md-3 col-md-pull-9   col-sidebar">

                    <?= $this->render('popular_post', ['business' => $business]) ?>

                    <?= $this->render('recent_comments', ['business' => $business]) ?>

                    <!-- block slide top -->
                    <div class="block-sidebar block-banner-sidebar">
                        <div class="owl-carousel"
                             data-nav="false"
                             data-dots="true"
                             data-margin="0"
                             data-items='1'
                             data-autoplayTimeout="700"
                             data-autoplay="true"
                             data-loop="true">
                            <div class="item item1" >
                                <img src="http://placehold.it/270x345" alt="images">
                            </div>
                            <div class="item item2" >
                                <img src="http://placehold.it/270x345" alt="images">
                            </div>
                            <div class="item item3" >
                                <img src="http://placehold.it/270x345" alt="images">
                            </div>
                        </div>
                    </div><!-- block slide top -->

                    <!-- block slide top -->
                    <div class="block-sidebar block-sidebar-testimonials2">

                        <div class="block-content">
                            <div class="owl-carousel"
                                 data-nav="false"
                                 data-dots="true"
                                 data-margin="0"
                                 data-items='1'
                                 data-autoplayTimeout="700"
                                 data-autoplay="true"
                                 data-loop="true">
                                <div class="item " >
                                    <div class="img">
                                        <img src="http://placehold.it/44x64" alt="icon1">
                                    </div>
                                    <strong class="title">100% Money Back Guaranteed</strong>
                                    <div class="des">
                                        Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt .
                                    </div>
                                    <a href="" class="btn">Read more <i aria-hidden="true" class="fa fa-angle-double-right"></i></a>
                                </div>

                            </div>
                        </div>
                    </div><!-- block slide top -->


                </div><!-- Sidebar -->



            </div>
        </div>


    </main><!-- end MAIN -->

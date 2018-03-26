<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Post[]
 * @var $pages Pagination
 * @var $business \common\models\Business
 */

use common\models\Lang;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;

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
                <h2 class="page-heading">
                    <span class="page-heading-title2">Новости</span>
                </h2>
                <div class="sortPagiBar clearfix">
                    <div class="bottom-pagination">
                        <nav>
                            <?= isset($pages) ? LinkPager::widget([
                                'pagination' => $pages,
                            ]) : ''; ?>
                        </nav>
                    </div>
                </div>
                <ul class="blog-posts">
                    <?php foreach ($models as $model) : ?>
                        <?= $this->render('_short_post', ['model' => $model, 'business' => $business]) ?>
                    <?php endforeach; ?>
                </ul>
                <div class="sortPagiBar clearfix">
                    <div class="bottom-pagination">
                        <nav>
                            <?= isset($pages) ? LinkPager::widget([
                                'pagination' => $pages,
                            ]) : ''; ?>
                        </nav>
                    </div>
                </div>
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
                        <div class="item item1">
                            <img src="http://placehold.it/270x345" alt="images">
                        </div>
                        <div class="item item2">
                            <img src="http://placehold.it/270x345" alt="images">
                        </div>
                        <div class="item item3">
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
                            <div class="item ">
                                <div class="img">
                                    <img src="http://placehold.it/44x64" alt="icon1">
                                </div>
                                <strong class="title">100% Money Back Guaranteed</strong>
                                <div class="des">
                                    Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor
                                    incididunt .
                                </div>
                                <a href="" class="btn">Read more <i aria-hidden="true"
                                                                    class="fa fa-angle-double-right"></i></a>
                            </div>

                        </div>
                    </div>
                </div><!-- block slide top -->

            </div><!-- Sidebar -->
        </div>
    </div>
</main><!-- end MAIN -->


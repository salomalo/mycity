<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Post[]
 * @var $pages Pagination
 * @var $business \common\models\Business
 */

use common\models\Lang;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;

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
                                Новости
                            </h1>

                        </div><!-- /.document-title -->


                        <div class="content" style="position: relative;">

                            <?php foreach ($models as $model) : ?>
                                <?= $this->render('_short_post', ['model' => $model, 'business' => $business]) ?>
                            <?php endforeach; ?>
                        </div><!-- /.content -->

                        <div style="margin-bottom: 50px">
                            <nav class="navigation pagination" role="navigation">
                                <?= isset($pages) ? SuperListLinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 6,
                                    'activePageCssClass' => 'page-numbers current',
                                    'options' => ['class' => 'nav-links'],
                                    'linkOptions' => [
                                        'class' => 'next page-numbers',
                                    ],
                                ]) : ''; ?>
                            </nav>
                        </div>

                    </div><!-- #primary -->
                </div><!-- /.col-* -->

            </div><!-- /.row -->
    </div><!-- /.main-inner -->
</div>

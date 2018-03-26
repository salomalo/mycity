<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Ads[]
 * @var $business \common\models\Business
 * @var $pages \yii\data\Pagination
 * @var $search string
 */


use common\models\Lang;
use yii\helpers\Html;
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
            <div class="col-md-9 col-md-push-3  col-main">

                <!-- Toolbar -->
                <div class=" toolbar-products toolbar-top">

                    <h1 class="cate-title">
                        <?php if ($models): ?>
                            Результат поиска по запросу <?= $search ?> :
                        <?php else: ?>
                            По запросу "<?= $search ?>" ничего не найдено
                        <?php endif; ?>
                    </h1>

                    <div class="modes">
                        <strong class="label">View as:</strong>
                        <strong class="modes-mode active mode-grid" title="Grid">
                            <span>grid</span>
                        </strong>
                    </div><!-- View as -->

                </div><!-- Toolbar -->

                <!-- List Products -->
                <div class="products  products-grid" style="min-height: 350px;">
                    <ol class="product-items row">
                        <?php foreach ($models as $ad) : ?>
                            <?= $this->render('view/_short_ads', ['model' => $ad, 'business' => $business]) ?>
                        <?php endforeach; ?>
                    </ol><!-- list product -->
                </div> <!-- List Products -->

                <!-- Toolbar -->
                <div class=" toolbar-products toolbar-bottom">
                    <?= isset($pages) ? LinkPager::widget([
                        'pagination' => $pages,
                    ]) : ''; ?>

                </div><!-- Toolbar -->

            </div><!-- Main Content -->


        </div>
    </div>
</main><!-- end MAIN -->


<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use yii\widgets\Breadcrumbs;

$this->title = $model->title . ' - О нас';
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
            <div class="col-md-12  col-main">
                <h2 class="page-heading">
                    <span class="page-heading-title2">О нас</span>
                </h2>

                <div class="content-text clearfix" style="min-height: 400px">
                    <?= $model->description ?>
                </div>

            </div><!-- Main Content -->




        </div>
    </div>


</main><!-- end MAIN -->

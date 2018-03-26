<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Post[]
 * @var $pages Pagination
 * @var $business \common\models\Business
 */

use common\models\Lang;
use frontend\extensions\StanzaLinkPager\StanzaLinkPager;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;

?>
<div class="innerHeading bg_f1f1f1 innerHeading-border">
        <div class="container text-center">
            <h1 class="marginBottomNone">Новости</h1>
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

<div id="content" class="blogPage style3">
        <div class="stripe-1">
            <div class="container" style="min-height: 150px;">
                <div class="row">
                    <?php foreach ($models as $model) : ?>
                        <?= $this->render('_short_post', ['model' => $model, 'business' => $business]) ?>
                    <?php endforeach; ?>
                </div>

                <div class="text-center">
                    <nav class="navigation pagination" role="navigation">
                        <?= isset($pages) ? StanzaLinkPager::widget([
                            'pagination' => $pages,
                            'maxButtonCount' => 7,
                            'activePageCssClass' => 'page-numbers current',
                            'options' => ['class' => 'nav-links'],
                            'linkOptions' => ['class' => 'next page-numbers'],
                        ]) : ''; ?>
                    </nav>
                </div>
            </div>
        </div><!-- ( STRIPE END ) -->
    </div><!-- ( CONTENT END ) -->


<?php
use common\models\Lang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/**@var $this \yii\web\View*/

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('css/new/startuply.css');
?>
<div class="main">
    <div class="main-inner">
        <div class="container">

            <div class="row">
                <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumb'],
                        'homeLink' => false,
                        'links' => $breadcrumbs,
                    ]); ?>
                <?php else : ?>
                    <?= Html::ul([['label' => Yii::t('app', 'Home'), 'url' => $main_string]], ['item' => function ($item, $index) {
                        if (!empty($item['url'])) {
                            return Html::tag('li', Html::a($item['label'], [$item['url']], []), ['class' => false]);
                        } else {
                            return Html::tag('li', $item['label'], ['class' => false]);
                        }
                    }, 'class' => 'breadcrumb']); ?>
                <?php endif; ?>

                <div class="col-sm-12">
                    <div id="primary">
                        <div class="reklama-title">
                            <div class="callout callout-success">
                                <h4><?= Yii::t('shopping-cart', 'Order_successfully_issued') ?></h4>

                                <p><?= Yii::t('shopping-cart', 'Expect_soon_you_contact_the_seller') ?></p>
                            </div>
                        </div>
                    </div><!-- /#primary -->
                </div><!-- /.col-* -->
            </div><!-- /.row -->

        </div><!-- /.content -->
    </div><!-- /.main-inner -->
</div>

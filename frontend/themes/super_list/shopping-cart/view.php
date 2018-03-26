<?php
use common\models\Lang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/**@var $this \yii\web\View*/

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
$main_string = ($canonical === Url::to($cur_lang, true)) ? null : '/';
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
                        'homeLink' => ['label' => $homeTitle, 'url' => $main],
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

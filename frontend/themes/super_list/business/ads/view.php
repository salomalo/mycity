<?php
/**
 * @var \common\models\Ads $model
 * @var $isEmptyCart
 * @var $count
 */

use common\models\Lang;
use frontend\extensions\AdBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
?>

<?= $this->render('view/simple_banner', ['model' => $model]) ?>

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

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                            <h1><?= $model ? '' : Yii::t('ads', 'No_ads_in_this_category') ?></h1>
                        </div>

                        <div class="content">

                            <div class="listing-detail">
                                <?= $this->render('view/images', ['model' => $model]) ?>

                                <?php if ($model->description) : ?>
                                    <?= $this->render('view/description', ['model' => $model]) ?>
                                <?php endif;?>

                                <?php if ($model->color) : ?>
                                    <?= $this->render('view/color', ['model' => $model]) ?>
                                <?php endif;?>

                                <?= $this->render('view/details', ['model' => $model]) ?>
                                <?php if ($model->trailer) : ?>
                                    <?= $this->render('view/trailer', ['model' => $model]) ?>
                                <?php endif; ?>
                                <?= $this->render('view/comments', ['model' => $model]) ?>
                            </div>

                        </div>
                    </div>
                </div>

                <?= $this->render('view/right_col', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>
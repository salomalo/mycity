<?php
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\CityPublic\CityPublic;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/**
 * @var yii\web\View $this
 * @var \common\models\City $model
 */

$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);

$this->title = $title;
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

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <div class="container-about">
                                <?php if ($model and $model->title) : ?>
                                    <h1 class="title-list"><?= $model->title ?></h1>
                                    <div class="list-head"><?= $model->about ?></div>
                                <?php elseif ($model) : ?>
                                    <h1 class="title-list"><?= $title ?></h1>
                                    <div class="list-head"><?= $model->about ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-sm-3 city-public-widget">
                                    <?= CityPublic::widget(['city' => Yii::$app->params['SUBDOMAINID'], 'net' => 'tw']); ?>
                                </div>
                                <div class="col-sm-3 city-public-widget">
                                    <?= CityPublic::widget(['city' => Yii::$app->params['SUBDOMAINID'], 'net' => 'fb']); ?>
                                </div>
                                <div class="col-sm-3 city-public-widget">
                                    <?= CityPublic::widget(['city' => Yii::$app->params['SUBDOMAINID'], 'net' => 'vk']); ?>
                                </div>
                            </div>
                        </div><!-- /.document-title -->

                        <div class="listings-row">

                        </div><!-- /.listings-row -->


                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div id="secondary" class="secondary sidebar">
                        <?= AdBlock::getLeft(); ?>
                    </div><!-- /#secondary -->
                </div>

            </div>
        </div>
    </div>
</div>
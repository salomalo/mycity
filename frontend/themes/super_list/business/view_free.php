<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 * @var $isShowDescription boolean
 */

use common\models\Ads;
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

<?php if ($model->isCategoryCafe()):?>
<div class="main" itemscope itemtype="http://schema.org/Restaurant">
    <?php else: ?>
    <div class="main" itemscope itemtype="http://schema.org/LocalBusiness">
        <?php endif;?>
        <?= $this->render('view/simple_banner', ['model' => $model, 'backgroundDisplay' => true]) ?>

        <?= $this->render('view/detail_menu', ['model' => $model, 'enabled' => [
            'contact' => true,
            'location' => true,
            'comments' => true,
            'goods' => $isGoods,
            'action' => $isAction,
            'afisha' => $isAfisha,
        ]]) ?>
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
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
                    </div>

                    <div class="col-sm-8 col-lg-9">
                        <div id="primary">
                            <div class="content">
                                <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                                <div class="listing-detail">
                                    <?= $this->render('view/contact_free', ['model' => $model]) ?>
                                    <?php if ($isShowDescription && $model->description != '') : ?>
                                        <?= $this->render('view/description', ['model' => $model]) ?>
                                    <?php endif; ?>
                                    <?= $this->render('view/location', ['model' => $model]) ?>
                                    <?= $this->render('view/comments', ['model' => $model]) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $this->render('view/right_col_free', ['model' => $model]) ?>
                </div>
            </div>
        </div>
    </div>
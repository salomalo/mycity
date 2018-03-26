<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $actions \common\models\Action[]
 * @var $categories integer[]
 * @var $backgroundDisplay
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 */

use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListAction\BlockListAction;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$cur_lang = Lang::getCurrent()->url;
$canonical = str_replace('/site', '', Url::current([], 'http'));
$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
?>

<?= $this->render('view/simple_banner', ['model' => $model, 'backgroundDisplay' => $backgroundDisplay]) ?>
<?= $this->render('view/detail_menu', ['model' => $model, 'enabled' => [
    'detail' => true,
    'goods' => $isGoods,
    'action' => $isAction,
    'afisha' => $isAfisha,
]]) ?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">

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

                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                            <h1>
                                <?= $actions ? Yii::t('action', 'seo_title_no_city') : Yii::t('action', 'Not Found') ?>
                            </h1>
                        </div>

                        <div class="listings-row" style="margin-bottom: 25px;">

                            <?php if (!empty($actions)) : ?>
                                <?php foreach ($actions as $model) : ?>
                                    <?= $this->render('_action_short', ['model' => $model]) ?>
                                <?php endforeach; ?>
                            <?php endif;?>

                        </div>

                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div id="secondary" class="secondary sidebar">
                        <?= BlockListAction::widget(['template' => 'index_super_list']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
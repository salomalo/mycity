<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Afisha
 */
use common\models\Lang;
use frontend\extensions\AdBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$alias = ($this->context->aliasCategory)? $this->context->aliasCategory : null;
$action = Yii::$app->controller->action->id;
$archive = Yii::$app->request->get('archive', '');

$pid = ($this->context->aliasCategory)? $this->context->aliasCategory : null;
$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
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
                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>
                        </div>

                        <?= $this->render('_form', ['model' => $model, 'checkCompany' => $checkCompany, 'modelCompanyId' => $modelCompanyId]) ?>

                        <div class="listings-row">
                        </div>


                    </div>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <div class="secondary sidebar my-filter-afisha">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

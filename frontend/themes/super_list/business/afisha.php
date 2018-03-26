<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $afisha \common\models\Afisha[]
 * @var $categories integer[]
 * @var $backgroundDisplay
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 */

use common\models\AfishaCategory;
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListAfisha\BlockListAfisha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$cur_lang = Lang::getCurrent()->url;
$canonical = str_replace('/site', '', Url::current([], 'http'));
$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);

$business_id = $model->id;
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

                            <h1><?= Yii::t('afisha', 'All_events') ?></h1>
                        </div>

                        <div class="listings-row" style="margin-bottom: 25px;">

                            <?php if (!empty($afisha)) : ?>
                                <?php foreach ($afisha as $model) : ?>
                                    <?php if ($model->isFilm) : ?>
                                        <?php
                                        $isShowAfisha = false;
                                        if ($schedule = $model->schedule(date("Y-m-d"))) {
                                            foreach ($schedule as $temp){
                                                if ($temp->company && $temp->idCompany == $business_id) {
                                                    $isShowAfisha = true;
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <?php if ($isShowAfisha) : ?>
                                            <?= $this->render('_afisha_short_film', [
                                                'model' => $model,
                                                'time' => null,
                                                'date' => date("Y-m-d"),
                                                'business_id' => $business_id
                                            ]) ?>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php
                                        $company = null;
                                        if ($model->idsCompany and isset($model->idsCompany[0]) and isset($business[$model->idsCompany[0]])) {
                                            $company = $business[$model->idsCompany[0]];
                                        }
                                        ?>
                                        <?= $this->render('_afisha_short', [
                                            'model' => $model,
                                            'time' => null,
                                            'date' => date("Y-m-d"),
                                            'company' => $company,
                                        ]) ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif;?>

                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div id="secondary" class="secondary sidebar">
                        <?= BlockListAfisha::widget([
                            'title' => Yii::t('afisha', 'Сategory_Posters'),
                            'className' => AfishaCategory::className(),
                            'path' => 'afisha/index',
                            'attribute' => 'pid',
                            'template' => 'super_list',
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
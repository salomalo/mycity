<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\WorkResume
 */

use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\CommentsSuperlist\CommentsSuperlist;
use frontend\extensions\Related\Related;
use yii\helpers\Html;
use common\models\File;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$canonical = str_replace('/site', '', Url::current([], 'http'));
$cur_lang = Lang::getCurrent()->url;
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
                        <div class="content">
                            <div class="document-title">
                            </div>

                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                            <div class="listing-detail">
                                <?= $this->render('view/description', ['model' => $model]) ?>

                                <div class="listing-detail-section" id="listing-detail-section-task">
                                    <h2 class="page-header"><?= Yii::t('vacantion', 'Description') ?></h2>

                                    <div class="listing-detail-description-wrapper">
                                        <div><?= Html::decode($model->description) ?></div>
                                    </div>
                                </div>

                                <?= CommentsSuperlist::widget(['id' => $model->id, 'type' => File::TYPE_RESUME]) ?>
                            </div>

                        </div>
                    </div>
                </div>

                <?= $this->render('view/right_col', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>

<?= Related::widget([
    'title' => Yii::t('vacantion', 'Similar_positions_Filed'),
    'idCategory' => $model->idCategory,
    'idModel' => $model->id,
    'idCity' => (!empty(Yii::$app->params['SUBDOMAINID'])) ? Yii::$app->params['SUBDOMAINID'] : null,
    'model' => 'common\models\WorkVacantion',
    'limit' => 4,
    'view' => 'vacantion'
]) ?>
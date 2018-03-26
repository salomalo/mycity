<?php
/**
 * @var $model \common\models\Afisha
 * @var $time
 */

use common\models\File;
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\CommentsSuperlist\CommentsSuperlist;
use yii\helpers\Html;
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
                                <div class="listing-detail-section" id="listing-detail-section-task">
                                    <h2 class="page-header"><?= Yii::t('afisha', 'Film'), ' "', $model->title, '". ',
                                        Yii::t('afisha', 'Poster_schedule_for_today_{cityTitle}', ['cityTitle' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]) ?>
                                    </h2>

                                    <div class="listing-detail-description-wrapper">
                                        <div>
                                            <div class="sobitie">
                                                <div class="sobitie-img">
                                                        <div class="listing-detail-gallery-wrapper">
                                                            <div class="listing-detail-gallery">
                                                                <?php $span = Html::tag('span', null, ['class' => 'item-image', 'data' => ['background-image' => Yii::$app->files->getUrl($model, 'image', 285)]]); ?>
                                                                <?= Html::a($span, Yii::$app->files->getUrl($model, 'image', 285), ['rel' => 'listing-gallery', 'data' => ['item-id' => 1]])?>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="characters full-characters">
                                                    <?php if ($model->genre) : ?>
                                                        <div class="ganr">
                                                            <?= Yii::t('afisha', 'Genre') ?> <span><?= $model->genreNames($model->genre) ?></span>
                                                        </div>
                                                    <?php endif;?>

                                                    <?php if ($model->country) : ?>
                                                        <div class="country">
                                                            <?= Yii::t('afisha', 'Country') ?> <span><?= $model->country ?></span>
                                                        </div>
                                                    <?php endif;?>

                                                    <?php if ($model->year) : ?>
                                                        <div class="year">
                                                            <?= Yii::t('afisha', 'Graduation_Year') ?> <span><?= $model->year ?></span>
                                                        </div>
                                                    <?php endif;?>

                                                    <?php if ($model->director) : ?>
                                                        <div class="regisser">
                                                            <?= Yii::t('afisha', 'Producer') ?> <span><?= $model->director ?></span>
                                                        </div>
                                                    <?php endif;?>

                                                    <?php if ($model->actors) : ?>
                                                        <div class="actors">
                                                            <?= Yii::t('afisha', 'Actors') ?> <span><?= $model->actors ?></span>
                                                        </div>
                                                    <?php endif;?>

                                                    <?php if ($model->budget) : ?>
                                                        <div class="money">
                                                            <?= Yii::t('afisha', 'Budget') ?> <span><?= $model->budget ?></span>
                                                        </div>
                                                    <?php endif;?>

                                                    <?php if ($model->tags) : ?>
                                                        <div class="where-text">
                                                            <i class="glyphicon glyphicon-tags"></i><?= $model->tags ?>
                                                        </div>
                                                    <?php endif;?>

                                                    <div class="text where-text">
                                                        <?= $model->fullText?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?= $this->render('view/seanse', ['model' => $model, 'time' => $time, 'date' => $date]) ?>

                                <?php if ($model->trailer): ?>
                                    <div class="listing-detail-section" id="listing-detail-section-trailler">
                                        <div class="video">
                                            <h2><?= Yii::t('afisha', 'Trailer') ?> "<?= $model->title ?>":</h2>
                                            <div class="listing-detail-attributes">
                                                <iframe width="560" height="315"
                                                        src="//www.youtube.com/embed/<?= $model->trailer ?>"
                                                        frameborder="0" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <?php if (Yii::$app->params['isCommentsEnabled']) : ?>
                                    <?= CommentsSuperlist::widget(['id' => $model->id, 'type' => File::TYPE_AFISHA]) ?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>

                <?= $this->render('view/right_col', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>
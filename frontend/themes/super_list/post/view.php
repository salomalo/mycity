<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Post
 * @var $photo
 */

use common\models\Lang;
use frontend\extensions\AdBlock;
use common\extensions\Gallery\Gallery;
use common\models\File;
use frontend\extensions\CommentsSuperlist\CommentsSuperlist;
use frontend\extensions\Related\Related;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$date = new DateTime($model->dateCreate);
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
                                    <h2 class="page-header"><?= $model->title ?></h2>

                                    <?= $this->render('view/gallery', ['model' => $model]) ?>
                                </div>

                                <div class="listing-detail-section" id="listing-detail-section-descr">
                                    <h2 class="page-header"><?= Yii::t('post', 'Description') ?></h2>
                                    <div class="listing-detail-description-wrapper">
                                        <div class="text where-text"><?= $model->fullText ?></div>
                                    </div>
                                </div>

                                <?php if(is_array($model->video) && count($model->video) > 0 && !empty($model->video[0])) : ?>
                                    <div class="listing-detail-section" id="listing-detail-section-video">
                                        <h2 class="page-header"><?= Yii::t('post', 'Video') ?></h2>
                                        <div class="listing-detail-description-wrapper">
                                            <div class="video">
                                                <?php foreach ($model->video as $video) : ?>
                                                    <?php if (is_string($video) and !empty($video)) : ?>
                                                        <?= Html::tag('iframe', null, ['width' => 560, 'height' => 315, 'src' => "//www.youtube.com/embed/$video", 'frameborder' => 0, 'allowfullscreen']) ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>

                                <div class="listing-detail-section" id="listing-detail-section-relative">
                                    <h2 class="page-header"><?= Yii::t('post', 'Relative') ?></h2>
                                    <div class="listing-detail-description-wrapper">
                                        <?= Related::widget([
                                            'idModel' => $model->id,
                                            'model' => 'common\models\Post',
                                            'limit' => 3,
                                            'view' => 'post_super_list',
                                            'idCity' => (!empty(Yii::$app->params['SUBDOMAINID']))? Yii::$app->params['SUBDOMAINID'] : null,
                                            'city' => (!empty(Yii::$app->params['SUBDOMAINTITLE']))? Yii::$app->params['SUBDOMAINTITLE'] : '',
                                        ]) ?>
                                    </div>
                                </div>

                                <?php if (Yii::$app->params['isCommentsEnabled']) : ?>
                                    <?= CommentsSuperlist::widget(['id' => $model->id, 'type' => File::TYPE_POST]) ?>
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
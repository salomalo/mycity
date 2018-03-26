<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Afisha
 * @var $showdate string
 * @var $time string
 */

use common\models\Lang;
use frontend\extensions\CommentsSuperlist\CommentsSuperlist;
use frontend\extensions\Share42\Share42;
use yii\helpers\Html;
use frontend\extensions\AdBlock;
use common\models\File;
use common\models\User;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$alias = isset($model->companys[0]) ? "{$model->companys[0]->id}-{$model->companys[0]->url}" : null;

if (isset($model->companys[0])) {
    $business_img = Html::img(Yii::$app->files->getUrl($model->companys[0], 'image', 65), ['alt' => $model->companys[0]->title]);
} else {
    $business_img = null;
}
$sobitie_img = $model->image ?
    Html::a(
        Html::img(Yii::$app->files->getUrl($model, 'image', 285), ['alt' => $model->title,'itemprop'=>"image"]),
        Yii::$app->files->getUrl($model, 'image'),
        ['class' => 'fancybox']
    ) : Html::img(Yii::$app->files->getUrl($model, 'image', 285), ['alt' => $model->title, 'style' => 'width:285px; height:225px;','itemprop'=>"image"]);
$user = Yii::$app->user->identity;
$owner = isset($model->companys[0]) ? $model->companys[0]->idUser : null;
$to_update = ($user and ($owner === $user->id or $user->role === User::ROLE_EDITOR)) ?
    Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/afisha/update', 'alias' => "{$model->id}-{$model->url}"]) : '';
$canonical = str_replace('/site', '', Url::current([], 'http'));
$cur_lang = Lang::getCurrent()->url;
$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
?>
<div itemscope itemtype="http://schema.org/Event">
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
                                <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                                <div class="listing-detail">

                                    <?= $this->render('view/gallery', ['model' => $model]) ?>

                                    <div class="listing-detail-section" id="listing-detail-section-task">
                                        <h2 class="page-header"><?= Yii::t('action', 'Details') ?></h2>
                                        <div class="listing-detail-description-wrapper">
                                            <div class="sobitie full-sobitie">
                                                <div class="characters">
                                                    <div class="text where-text" itemprop="description"><?= $model->description ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?= $this->render('view/where', ['model' => $model]) ?>

                                    <?php if($model->trailer) : ?>
                                        <div class="video">
                                            <div class="listing-detail-section" id="listing-detail-section-trailler">
                                                <h2 class="page-header"><?= Yii::t('afisha', 'Video') ?> "<?= $model->title ?>":</h2>
                                                <div class="video">
                                                    <div class="listing-detail-attributes">
                                                        <iframe width="560" height="315" src="//www.youtube.com/embed/<?= $model->trailer ?>" frameborder="0" allowfullscreen></iframe>
                                                    </div>
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
</div>
<?php
use common\models\Lang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = Yii::t('app', 'Support');
$this->params['breadcrumbs'][] = $this->title;

$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="reklama-title">

                            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                                <?= Breadcrumbs::widget([
                                    'tag' => 'ol',
                                    'options' => ['class' => 'breadcrumb'],
                                    'homeLink' => ['label' => $homeTitle, 'url' => $main],
                                    'links' => $breadcrumbs,
                                ]) ?>
                            <?php endif; ?>

                            <div class="modal-header our-modal-header">
                                <h4><?= Yii::t('app', 'Support') ?></h4>
                            </div>

                            <div class="modal-body our-modal-body">
                                <div class="descr">
                                    <p><?= Yii::t('app', 'To_support') ?> <?= Html::a(Yii::$app->params['contactEmail'], 'mailto:' . Yii::$app->params['contactEmail']) ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * @var \common\models\Ads $model
 * @var $isEmptyCart
 * @var $count
 */

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

<?= $this->render('view/simple_banner', ['model' => $model]) ?>
<?= $this->render('view/detail_menu') ?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>
                        </div>

                        <div class="content">

                            <div class="listing-detail">
                                <?= $this->render('view/description', ['model' => $model]) ?>
                                <?= $this->render('view/details', ['model' => $model]) ?>
                                <?= $this->render('view/images', ['model' => $model]) ?>
                                <?= $this->render('view/comments', ['model' => $model]) ?>
                            </div>

                        </div>
                    </div>
                </div>

                <?= $this->render('view/right_col', ['model' => $business]) ?>
            </div>
        </div>
    </div>
</div>
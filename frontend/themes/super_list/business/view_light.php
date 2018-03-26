<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 * @var $isShowDescription boolean
 */

use common\models\Lang;
use yii\helpers\Url;

$cur_lang = Lang::getCurrent()->url;
$canonical = str_replace('/site', '', Url::current([], 'http'));

$cf = [];
if ($model->customFieldValues) {
    foreach ($model->customFieldValues as $item) {
        $cf[$item->customField->title][] = $item->anyValue;
    }
    foreach ($cf as &$item) {
        $item = implode(', ', $item);
    }
}
?>

<?php if ($model->isCategoryCafe()):?>
<div class="main" itemscope itemtype="http://schema.org/Restaurant">
    <?php else: ?>
    <div class="main" itemscope itemtype="http://schema.org/LocalBusiness">
        <?php endif;?>
        <?= $this->render('view/simple_banner', ['model' => $model, 'backgroundDisplay' => true]) ?>

        <?= $this->render('view/detail_menu', ['model' => $model, 'enabled' => [
            'description' => true,
            'attributes' => !!$cf,
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

                    <div class="col-sm-8 col-lg-9">
                        <div id="primary">
                            <div class="content">
                                <div class="listing-detail">
                                    <?= $this->render('view/contact', ['model' => $model]) ?>

                                    <?php if ($isShowDescription && $model->description != '') : ?>
                                        <?= $this->render('view/description', ['model' => $model]) ?>
                                    <?php endif; ?>

                                    <?php if ($cf) : ?>
                                        <?= $this->render('view/attributes', ['cf' => $cf]) ?>
                                    <?php endif; ?>

                                    <?= $this->render('view/categories', ['model' => $model]) ?>
                                    <?= $this->render('view/location', ['model' => $model]) ?>
                                    <?= $this->render('view/comments', ['model' => $model]) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $this->render('view/right_col', ['model' => $model]) ?>
                </div>
            </div>
        </div>
    </div>
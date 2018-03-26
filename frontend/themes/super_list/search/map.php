<?php
use common\extensions\MultiView\MultiView;
use common\models\BusinessAddress;
use frontend\extensions\AdBlock;
use frontend\extensions\SearchForm\SearchForm;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * @var $listAddress array
 * @var $s string
 * @var $pid string
 * @var $titleCategory string
 */
?>
<div class="main-inner">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-lg-9">
                <div id="primary">
                    <h1 class="title-list">
                        <?= $pid ? Yii::t('business', 'Map_category_{city_ge}_{category}', ['city_ge' => Yii::$app->params['SUBDOMAIN_TITLE_GE'], 'category' => $titleCategory])
                            : Yii::t('business', 'map_{city}', ['city' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]) ?>:
                    </h1>
                    <?= AdBlock::getTop() ?>

                    <?= $this->render('_search_form_business.php', [
                        'pid' => $pid
                    ]) ?>

                    <?= MultiView::widget([
                        '_view' => '_address_map',
                        'data' => $listAddress,
                        'relModelName' => BusinessAddress::className(),
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

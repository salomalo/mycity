<?php

use common\extensions\MultiView\MultiView;
use common\models\BusinessAddress;
use frontend\extensions\AdBlock;
use frontend\extensions\SearchForm\SearchForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $pages \yii\data\Pagination
 * @var $models \common\models\Business[]
 * @var $list \common\models\Business[]
 *
 * @var $top false
 * @var $isSelect false
 *
 * @var $listAddress array
 *
 * @var $search string
 * @var $pid string
 * @var $start_time string
 * @var $end_time string
 * @var $weekDay string
 * @var $activeTab string
 *
 * @var $titleCategory string|null
 */

$pid = $this->context->alias_category;
$listAddress = $this->context->listAddress;
$activeTab = Yii::$app->session->get('viewBusiness');
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

        <?= $this->render('_search_form.php', ['pid' => $pid, 'options' => [
            'action' => 'map',
            'pid' => $pid,
            'index' => ['pid' => $pid, 'url' => '/business/index', 'class' => ''],
            'map' => ['pid' => null, 'url' => '/business/map', 'class' => 'active'],
        ]]) ?>

        <?php echo MultiView::widget([
            '_view' => '_address_map',
            'data' => $listAddress,
            'relModelName' => BusinessAddress::className(),
        ]); ?>
    </div>
    </div>
    </div>
    </div>
    </div>

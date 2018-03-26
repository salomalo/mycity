<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\extensions\MultiView\MultiView;
use common\models\BusinessAddress;

$city = Yii::$app->request->city;
?>

<div class="listing-detail-section" id="listing-detail-section-location">
    <h2 class="page-header">
        <?= $model->title, ' ', Yii::t('business', 'on_the_map', ['city_ge' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]) ?>:
    </h2>

    <div class="listing-detail-location-wrapper">

        <ul id="listing-detail-location" class="nav nav-tabs" role="tablist">

            <li role="presentation" class="nav-item active">
                <a href="#simple-map-panel" aria-controls="simple-map-panel" role="tab" data-toggle="tab" class="nav-link">
                    <?= Yii::t('business', 'map')?>
                </a>
            </li>
        </ul>


        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="simple-map-panel">
                <div class="detail-map">
                    <div class="map-position">
                        <?php $selectCity = $city ? $city->title : null; ?>
                        <?php $selectCity = ($city and ($city->subdomain === 'dnepr')) ? 'Днепропетровск' : $selectCity; ?>
                        <?= MultiView::widget([
                            '_view' => '_address',
                            'data' => $this->context->listAddress,
                            'selectCity' => $selectCity,
                            'relModelName' => BusinessAddress::className(),
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
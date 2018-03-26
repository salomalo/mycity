<?php

use common\models\AdsColor;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AdsColor */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $adsId string */

$this->title = 'Список цветов товара';
$this->params['breadcrumbs'][] = $this->title;

$ads = \common\models\Ads::findOne($adsId);
?>
<div class="ads-color-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить цвет', ['ads-color/create', 'adsId' => $adsId], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Перейти  к товару', ['ads/update', 'id' => (string)$ads->_id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'isShowOnBusiness',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'isShowOnBusiness',
                    'data' => AdsColor::$statusEnabledColor,
                    'options' => ['placeholder' => 'Выберите статус'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
                'value' => function (AdsColor $model) {
                    if (array_key_exists($model->isShowOnBusiness, AdsColor::$statusEnabledColor)) {
                        return AdsColor::$statusEnabledColor[$model->isShowOnBusiness];
                    } else {
                        return '';
                    }

                },
            ],
            [
                'label' => 'Картинка',
                'format' => 'html',
                'options' => ['width' => '110px'],
                'value' => function (\common\models\AdsColor $model) {
                    return $model->image ? Html::img(Yii::$app->files->getUrl($model, 'image'), ['width' => '100']) : null;
                },
            ],
//            'idAds',

            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>
</div>

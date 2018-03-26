<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BusinessCustomField */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('business_custom_field', 'Business Custom Fields');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-custom-field-index">

    <p>
        <?= Html::a(Yii::t('business_custom_field', 'Create Business Custom Field'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'multiple',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use common\models\Notification;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Notification */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Notification', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sender_id',
            [
                'attribute' => 'status',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => Notification::$listStatus,
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
                'value' => function (Notification $model) {
                    if (isset(Notification::$listStatus[$model->status])) {
                        return Notification::$listStatus[$model->status];
                    } else {
                        return '';
                    }
                },
            ],
            'title',
            'link',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

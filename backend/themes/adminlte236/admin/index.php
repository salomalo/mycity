<?php

use backend\models\Admin;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Account $searchModel
 */

$this->title = Yii::t('user', 'Admins');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <p><?= Html::a(Yii::t('user', 'Create_admin'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '60'],
            ],
            'username',
            [
                'attribute' => 'level',
                'headerOptions' => ['width' => '150'],
                'value' => function($model) {
                    return isset(Admin::$levels[$model->level]) ? Admin::$levels[$model->level] : $model->level;
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Admin::$levels,
                    'attribute' => 'level',
                    'options' => [
                        'placeholder' => 'Выберите уровень',
                        'id' => 'level',
                    ],
                    'pluginOptions' => ['allowClear' => true,]
                ]),
            ],
            [
                'attribute' => 'dateCreate',
                'format' => 'date',
                'headerOptions' => ['width' => '150'],
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'dateCreate',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Упр.',
                'headerOptions' => ['width' => '50'],
                'template' => '{update}{delete}{link}',
            ],
        ],
    ]); ?>

</div>

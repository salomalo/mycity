<?php
/**
 * @var $this yii\web\View
 * @var $searchModel common\models\search\Log
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $allLogs integer
 */

use common\models\Log;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Логи аутентификации';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="action-index">
    <div style="margin-bottom: 15px">
        <?php if ($allLogs) :?>
            <a href="<?= Url::to(['/admin-log/auth']) ?>">
                <input type="checkbox"> Не показывать логи админа <br/>
            </a>
        <?php else : ?>
            <a href="<?= Url::to(['/admin-log/auth', 'allLogs' => 1]) ?>">
                <input type="checkbox" checked> Не показывать логи админа <br/>
            </a>
        <?php endif; ?>
    </div>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'attribute' => 'dateCreate',
                'value' => function (\common\models\Log $model) {
                    $date = new DateTime($model->dateCreate);
                    return $date->format('Y-m-d, H:i:s');
                },
                'filter' => \yii\jui\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'dateCreate',
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd'
                ]),
            ],
            [
                'attribute' => 'description',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'description',
                    'data' => [
                        Log::$types[Log::TYPE_LOGIN] => Log::$types[Log::TYPE_LOGIN],
                        Log::$types[Log::TYPE_REGISTER] => Log::$types[Log::TYPE_REGISTER],
                    ],
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function (\common\models\Log $model) {
                    $user = \common\models\User::findOne($model->user_id);
                    $url = Url::to(['/user/view', 'id' => $model->user_id]);
                    $link = Html::a(isset($user->username) ? $user->username : $model->user_id, $url);
                    return $link;
                },
            ],
            [
                'attribute' => 'admin_id',
            ],
            [
                'attribute' => 'ip',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'0px'],
                'template' => '',
            ],
        ],
    ]);
    ?>
</div>
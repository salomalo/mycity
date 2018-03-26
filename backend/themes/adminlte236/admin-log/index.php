<?php
/**
 * @var $this yii\web\View
 * @var $allLogs integer
 * @var $searchModel common\models\search\Log
 * @var $dataProvider yii\data\ActiveDataProvider
 */
use common\models\Log;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\helpers\Url;

$this->title = 'Логи Админов';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="action-index">
    <p>
        <?= Html::a(Yii::t('detail-log', 'Charts'), ['charts'], ['class' => 'btn btn-success']) ?>
    </p>
    <div style="margin-bottom: 15px">
        <?php if ($allLogs) :?>
            <a href="<?= Url::to(['/admin-log']) ?>">
                <input type="checkbox"> Не показывать логи админа <br/>
            </a>
        <?php else : ?>
            <a href="<?= Url::to(['/admin-log', 'allLogs' => 1]) ?>">
                <input type="checkbox" checked> Не показывать логи админа <br/>
            </a>
        <?php endif; ?>
    </div>

    <ul class="nav nav-tabs">
        <li class="active">
            <?= Html::a('Все логи', ['admin-log/index']) ?>
        </li>    
        <li>
            <?= Html::a('Ежедневный отчет', ['admin-log/trunc', 'trunc' => 'day']) ?>
        </li>    
        <li>    
            <?= Html::a('Недельный отчёт', ['admin-log/trunc', 'trunc' => 'week']) ?>
        </li>    
    </ul>    


    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn', 
                'options' => ['width'=>'70px']
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
                'attribute' => 'type',
                'options' => ['width'=>'150px'],
                'value' => function (\common\models\Log $model) {
                    if (array_key_exists($model->type, Log::$types)) {
                        return Log::$types[$model->type];
                    } else {
                        return '';
                    }

                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'type',
                    'data' => Log::$types,
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'description',
            ],
            [
                'attribute' => 'object_id',
                'format' => 'html',
                'value' => function (\common\models\Log $model) {
                    return $model->getBackendLinkPid();
                },
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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SystemLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'System Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-view">


    <p>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-success']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'dateCreate',
            'description:html',
            'status'
        ],
    ]) ?>

</div>

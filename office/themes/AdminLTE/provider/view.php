<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Provider */

$this->title = 'Поставщик' . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('provider', 'Providers'), 'url' => ['/user/settings/providers']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-view">

    <p>
        <?= Html::a(Yii::t('provider', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description',
        ],
    ]) ?>

</div>
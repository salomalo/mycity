<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Provider */

$this->title = Yii::t('provider', 'Update provider') . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('provider', 'Providers'), 'url' => ['/user/settings/providers']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
?>
<div class="provider-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

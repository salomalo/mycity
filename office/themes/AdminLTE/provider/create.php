<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Provider */

$this->title = Yii::t('provider', 'Create provider');
$this->params['breadcrumbs'][] = ['label' => Yii::t('provider', 'Providers'), 'url' => ['/user/settings/providers']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

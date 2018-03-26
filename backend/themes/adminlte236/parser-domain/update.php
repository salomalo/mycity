<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ParserDomain */

$this->title = 'Update Parser Domain: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parser Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parser-domain-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

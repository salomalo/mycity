<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ParserDomain */

$this->title = 'Create Parser Domain';
$this->params['breadcrumbs'][] = ['label' => 'Parser Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parser-domain-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

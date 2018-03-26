<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ParseKino */

$this->title = 'Create Parse Kino';
$this->params['breadcrumbs'][] = ['label' => 'Parse Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-kino-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

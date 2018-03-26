<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */

$this->title = 'Create Work Resume';
$this->params['breadcrumbs'][] = ['label' => 'Work Resumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-resume-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

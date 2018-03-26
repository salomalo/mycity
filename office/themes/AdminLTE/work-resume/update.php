<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'My_resume'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('resume', 'Update');
?>
<div class="work-resume-update">
    <h3><?= Yii::t('resume', 'Update') . ' - ' . $model->title?></h3>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

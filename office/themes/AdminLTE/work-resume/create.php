<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */

$this->title = Yii::t('resume', 'Add_resume');
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'My_resume'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-resume-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

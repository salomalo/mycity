<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model common\models\User
 */

$this->title = Yii::t('user', 'Create');
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <?= $this->render('_form', ['model' => $model,]) ?>
</div>

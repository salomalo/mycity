<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Admin $model
 */

$this->title = Yii::t('user', 'Create_admin');
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>

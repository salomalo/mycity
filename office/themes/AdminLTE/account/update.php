<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Account $model
 */

$this->title = 'Редактирование Профиля: ' ;
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="account-update">

    <?= $this->render('_form', [
        'model' => $model,
        'isDateInFuture' => isset($isDateInFuture) ? $isDateInFuture : false,
    ]) ?>

</div>

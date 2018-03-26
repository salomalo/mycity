<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\WorkVacantion */
/* @var $business common\models\Business */
if (isset($business->title)){
    $this->title = $business->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = 'Обновить - ' . ' ' . $model->title;
} else {
    $this->title = $model->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = 'Обновить - ' . ' ' . $model->title;
}


if ($business){
    $link = Url::to(['/action/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>
<?php if (isset($business->title)) :?>
    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'work-vacantion'])?>
<?php endif; ?>

<div class="work-vacantion-update">
    <h3><?= 'Обновить - ' . ' ' . $model->title ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

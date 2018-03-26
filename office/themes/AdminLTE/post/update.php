<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $business common\models\Business */

$this->title = $business->title;
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Обновить новость: ' . ' ' . $model->title;

if ($business){
    $link = Url::to(['/post/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>
<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'post'])?>

<div class="post-update">
    <h3><?= 'Обновить новость: ' . ' ' . $model->title ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'business' => $business,
        'listAddress'=>$listAddress
    ]) ?>

</div>

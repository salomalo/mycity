<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $business common\models\Business */

//$this->title = 'Создать новость';
//$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->title = $business->title;//'Новая Афиша ';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Создать новость';
if ($business){
    $link = Url::to(['/afisha/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>

<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'post'])?>

<div class="post-create">
    <h3>Создать новость</h3>

    <?= $this->render('_form', [
        'model' => $model,
        'business' => $business,
        'listAddress'=>$listAddress
    ]) ?>

</div>

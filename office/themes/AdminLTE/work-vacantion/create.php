<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\WorkVacantion */
/* @var $business common\models\Business */

//$this->title = 'Создать новую вакансию';
//$this->params['breadcrumbs'][] = ['label' => 'Work Vacantions', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->title = isset($business->title) ? $business->title : 'Создать новую вакансию';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Создать новую вакансию';

if ($business){
    $link = Url::to(['/work-vacantion/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>
<?php if($business): ?>
    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'work-vacantion'])?>
<?php endif; ?>

<div class="work-vacantion-create">
    <h3>Создание новой вакансии</h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Afisha */
/* @var $business common\models\Business */
/* @var $idCompany integer */
/* @var $isFilm boolean */

if ($business){
    $this->title = $business->title;//'Новая Афиша ';
    $link = Url::to(['/afisha/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}

$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Новая Афиша';
?>

<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'afisha'])?>

<div class="afisha-create">
    <h3>Создание новой афиши</h3>

    <?= $this->render(($isFilm)? '_form_film' : '_form', [
        'model' => $model,
        'idCompany' => $idCompany,
    ]) ?>

</div>

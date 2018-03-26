<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */

$this->title = $business->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, [
    'class' => 'btn bg-purple',
    'target' => '_blank',
    'style' => 'margin-left: 20px; margin-right: 15px;'
]);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'action'])?>

<div class="schedule-kino-create">

    <h1><?= 'Добавить расписание к фильму' ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Action */
/* @var $business common\models\Business */

$this->title = $business->title;
$this->params['breadcrumbs'][] = $business->title;
$this->params['breadcrumbs'][] = $model->title;

if ($business){
    $link = Url::to(['/action/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
?>
<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'action'])?>

<div class="action-view">
    <h3><?= $model->title ?></h3>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id, 'idCompany' => $business->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Создать', ['create', 'idCompany' => $business->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Посмотреть на сайте',
            $link,
            ['class' => 'btn bg-purple', 'target' => '_blank']
        );
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'idCompany',
                'value' => $model->companyName->title,
            ],
            [
                'attribute' => 'idCategory',
                'value' => $model->category->title,
            ],
            'image',
            'description:html',
            'price',
            'dateStart',
            'dateEnd',
        ],
    ]) ?>

</div>

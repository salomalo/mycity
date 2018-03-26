<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Afisha
 * @var $idCompany integer
 * @var $business common\models\Business
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = $business->title;
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Просмотр афиши';

if ($business){
    $link = Url::to(['/afisha/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}


$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
?>
<?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'afisha'])?>

<div class="afisha-view">

    <h3><?= $model->title ?></h3>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id, 'idCompany' => $idCompany], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id, 'idCompany' => $idCompany], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Создать', ['create', 'idCompany' => $idCompany], ['class' => 'btn btn-success']) ?>
<!--        --><?php //
//        echo Html::a('Список', ['index', 'idCompany' => $idCompany], ['class' => 'btn btn-info'])
//        ?>
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
                'attribute' => 'image',
                'format' => 'raw',
                'value' => $model->image ? Html::img(Yii::$app->files->getUrl($model, 'image', 70)) : '',
            ],
            [
                'attribute' => 'idsCompany',
                'value' => $model->companyNames($model->idsCompany),
            ],
            'description:html',
            'dateStart',
            'dateEnd',
            [
                'attribute' => 'times',
                'label' => Yii::t('afisha', 'Afisha_times'),
                'value' => is_array($model->times) ? implode(', ', $model->times) : $model->times,
            ],
            'price',
            'rating',
        ],
    ]) ?>
</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WorkVacantion */
/* @var $business common\models\Business */



if ($business){
    $this->title = $business->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = $model->title;

    $link = Url::to(['/work-vacantion/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
} else {
    $this->title = $model->title;
    $this->params['breadcrumbs'][] = 'Мои вакансии';
    $this->params['breadcrumbs'][] = $this->title;
}

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
?>
<?php if (isset($business->title)) :?>
    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'work-vacantion'])?>
<?php endif; ?>

<div class="work-vacantion-view">
    <p>
        <?php if (isset($business->title)) :?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id, 'idCompany' => $business->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Создать', ['create', 'id' => $model->idCategory, 'idCompany' => $business->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Создать', ['create', 'id' => $model->idCategory], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('Посмотреть на сайте',
            $link,
            ['class' => 'btn bg-purple', 'target' => '_blank']
        );?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'idCategory',
                'value' => $model->category ? $model->category->title : null,
            ],
            [
                'attribute' => 'idCompany',
                'value' => $model->company ? $model->company->title : null,
            ],
            [
                'attribute' => 'idCity',
                'value' => $model->city ? $model->city->title : null,
            ],
            [
                'attribute' => 'idUser',
                'value' => $model->user ? $model->user->username : null,
            ],
            'title',
            'name',
            [
                'attribute' => 'description',
                'value' => $model->description ? strip_tags(Html::decode($model->description)) : null,
            ],
            [
                'attribute' => 'proposition',
                'value' => $model->proposition ? strip_tags(Html::decode($model->proposition)) : null,
            ],
            'male',
            'experience:ntext',
            'salary',
            'minYears',
            'maxYears',
            'isFullDay',
            'isOffice',
            'phone',
            'email:email',
            'skype', 
            'dateCreate',
        ],
    ]) ?>

</div>

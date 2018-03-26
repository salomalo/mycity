<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'My_resume'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
?>
<div class="work-resume-view">
    <p>
        <?= Html::a(Yii::t('resume', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('resume', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('resume', 'Create'), ['create', 'id' => $model->idCategory], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('resume', 'List_My_resume'), ['index'], ['class' => 'btn btn-info']) ?>
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
                'value' => $model->category->title,
            ],
            [
                'attribute' => 'idUser',
                'value' => ($model->user)? $model->user->username : '',
            ],
            [
                'attribute' => 'idCity',
                'value' => ($model->city)? $model->city->title : '',
            ],
            [
                'attribute' => 'title',
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'description',
                'format' => 'html',
            ],
            [
                'attribute' => 'photoUrl',
            ],
            [
                'attribute' => 'year',
            ],
            [
                'attribute' => 'experience',
            ],
            [
                'attribute' => 'male',
                'label' => 'Пол',
                'value' => ($model->male)? 'Мужской' : 'Женский',
            ],
            [
                'attribute' => 'salary',
            ],
            [
                'attribute' => 'isFullDay',
                'label' => 'Рабочий день',
                'value' => ($model->isFullDay)? 'Полный ' : 'Не полный',
            ],
            [
                'attribute' => 'isOffice',
                'label' => 'Работа в офисе',
                'value' => ($model->isOffice)? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'phone',
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
            ],
            [
                'attribute' => 'skype',
            ],
            [
                'attribute' => 'dateCreate',
            ],
        ],
    ]) ?>

</div>

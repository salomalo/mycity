<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Предприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-view">
<?php Pjax::begin(['id' => 'new_note']) ?>
    <?=  DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'title',
           // 'idUser',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => ($model->image)? '<img src=' . \Yii::$app->files->getUrl($model, 'image', 65) . ' " >' : '',
            ],
            [
                'attribute' => 'idCategories',
                'type' => 'raw',
                //'value' => $model->categories,
                'value' => $model->categoryNames($model->idCategories),
            ],
            [
                'attribute' => 'idProductCategories',
                'type' => 'raw',
                //'value' => $model->productCategories,
                'value' => $model->productCategoryNames($model->idProductCategories),
            ],
            
            [
                'attribute' => 'idCity',
                'value' => ($model->idCity)? $model->city->title : '',
            ],
            [
                'label' => 'Адрес',
                'value' => ($model->address[0])? $model->address[0]['address'] : '',
            ],
            [
                'label' => 'Телефон',
                'value' => ($model->address[0])? $model->address[0]['phone'] : '',
            ],
            [
                'label' => 'График работы',
                'value' => '',
            ],
            [
                'label' => 'Пн - Пт',
                'value' => ($model->times && !empty($model->times[0]))? $model->times[0]->start . ' - ' . $model->times[0]->end : '',
            ],
            [
                'label' => 'Суббота',
                'value' => ($model->times && !empty($model->times[1]))? $model->times[1]->start . ' - ' . $model->times[1]->end : '',
            ],
            [
                'label' => 'Воскресенье',
                'value' => ($model->times && !empty($model->times[2]))? $model->times[2]->start . ' - ' . $model->times[2]->end : '',
            ],
        ],
    ]) 
            ?>
            
            <div class="col-xs-12  col-sm-12 text-center">
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]); ?>
                <?= Html::hiddenInput('isUpdate', true)?>
                <?= Html::hiddenInput('id', $model->id)?>
                
                <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary', 'data-pjax' => true])?>
                <?= Html::a('Добавить новое', ['/'], ['class' => 'btn btn-success', 'data-pjax' => true]) ?>
                
                <?php ActiveForm::end(); ?>
            </div>
    <?php Pjax::end() ?>
</div>
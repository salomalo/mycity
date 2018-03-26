<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\ProductCustomfield;

/**
 * @var yii\web\View $this
 * @var common\models\Ads $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <p>
        <?php
        echo Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']);
        echo Html::a('Delete', ['delete', 'id' => (string)$model->_id], ['class' => 'btn btn-danger',
            'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'post']]);
        echo  Html::a('Create', ['create', 'id' => $model->idCategory], ['class' => 'btn btn-success']);
        echo Html::a('List', ['index'], ['class' => 'btn btn-info']);
        ?>
    </p>
    <?php
    /** @noinspection PhpUndefinedFieldInspection */
    $attributes = [
            '_id',
            'title:text',
            'seo_description',
            'seo_keywords',
            'url:text',
            [
                'label' => 'video',
                'value' => ($model->video) ? implode(', ', $model->video) : '',
            ],
            [
                'label' => 'Картинка',
                'value' => ($model->image) ? Html::img(Yii::$app->files->getUrl($model, "image", 65)) : '',
                'format' => 'html',
            ],
            [
                'label' => 'idCategory',
                'value' => isset($model->category->title) ? $model->category->title : '',
            ],
            [
                'label' => 'idCompany',
                'value' => isset($model->company->title)? $model->company->title : '',
            ],
            [
                'label' => 'idUser',
                'value' => Html::a($model->idUser,['user/view', 'id' => $model->idUser],['target' => '_blank']),
                'format' => 'html',
            ],
            [
                'label' => 'idBusiness',
                'value' => isset($model->business->title)? $model->business->title : '',
            ],
            [
                'label' => 'idCity',
                'value' => isset($model->city->title)? $model->city->title : '',
            ],
            [
                'label' => 'model',
                'value' => isset($model->tovar->model)? $model->tovar->model : '',
            ],
            [
                'label' => 'contact',
                'value' => isset($model->contact) ? $model->contact : '',
            ],
            [
                'label' => 'trailer',
                'value' => isset($model->trailer) ? $model->trailer : '',
            ],
            [
                'label' => 'color',
                'format' => 'html',
                'value' => isset($model->color) ? '<div class="sp-preview-inner" style="background-color: ' . $model->color . ';width: 20px;height: 20px;"></div>' : '',
            ],
            'description:html',
            'price'
        ];
    if ($model->idCategory) {
        $cfs = ProductCustomfield::findAll(['idCategory' => $model->idCategory]);
        foreach ($cfs as $cf) {
            if (isset($model[$cf->alias])) {
                $attributes[] = ['label' => $cf->title, 'value'=>$model[$cf->alias]];
            }
        }
    }
    echo DetailView::widget(['model' => $model, 'attributes' => $attributes]);
    ?>
</div>
<?php if ($model->images) {
    foreach ($model->images as $img) {
        /** @noinspection PhpUndefinedFieldInspection */
        $image = \Yii::$app->files->getUrl($model, 'images', null, $img);
        echo Html::a(
            Html::img($image, ['class' => 'small', 'width' => 64]),
            $image,
            ['class' => 'fancybox', 'data-fancybox-group' => 'gallery', 'title' => '']
        );
    }
}?>

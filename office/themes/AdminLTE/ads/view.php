<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\ProductCustomfield;

/**
 * @var yii\web\View $this
 * @var common\models\Ads $model
 * @var $business common\models\Business
 */

if ($business){
    $this->title = $business->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = $model->title;

    $link = Url::to(['/ads/index', 'idCompany' => $business->id]);
    $btnSeeOnFrontend = Html::a('Назад', $link, ['class' => 'btn bg-purple', 'style' => 'margin-left: 20px']);

    $script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
} else {
    $this->title = $model->title;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['breadcrumbs'][] = $model->title;
}

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
?>
<?php if (isset($business->title)) :?>
    <?= $this->render('/business/top_block', ['id' => $business->id, 'active' => 'ads'])?>
<?php endif; ?>

<div class="product-view">
    <h3><?= $model->title ?></h3>

    <p>
        <?php
        echo Html::a(Yii::t('ads', 'Update'),
            ['update', 'id' => (string)$model->_id, 'idCompany' => isset($business->id) ? $business->id : null],
            ['class' => 'btn btn-primary']
        );
        echo Html::a(Yii::t('ads', 'Delete'),
            [
                'delete',
                'id' => (string)$model->_id
            ],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы действительно хотите удалить объявление ?',
                    'method' => 'post']
            ]);
        echo Html::a(Yii::t('ads', 'Create'), ['create', 'id' => $model->idCategory, 'idBusiness' => isset($business->id) ? $business->id : null], ['class' => 'btn btn-success']);
        //echo Html::a(Yii::t('ads', 'List_Ads'), ['index'], ['class' => 'btn btn-info']);
        echo Html::a('Посмотреть на сайте',
            $link,
            ['class' => 'btn bg-purple', 'target' => '_blank']
        );
        ?>
    </p>
    <?php
    /** @noinspection PhpUndefinedFieldInspection */
    $attributes = [
        '_id',
        'title',
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
            'label' => 'idUser',
            'value' => isset($model->user->username) ? $model->user->username : '',
        ],
        [
            'label' => 'idBusiness',
            'value' => ($model->business != null) ? $model->business->title : '',
        ],
        [
            'label' => 'Город',
            'value' => isset($model->city->title) ? $model->city->title : '',
        ],
        [
            'label' => 'Телефон',
            'value' => isset($model->contact) ? $model->contact : '',
        ],
        [
            'label' => 'Обзор',
            'value' => isset($model->trailer) ? $model->trailer : '',
        ],
        [
            'label' => 'Цвет товара',
            'format' => 'html',
            'value' => isset($model->color) ? '<div class="sp-preview-inner" style="background-color: ' . $model->color . ';width: 20px;height: 20px;"></div>' : '',
        ],
        'description:html',
        'price:text:Цена',
        [
            'label' => 'Скидка',
            'value' => isset($model->discount) ? $model->discount . '%' : '',
        ],
    ];
    if ($model->idCategory) {
        $cfs = ProductCustomfield::findAll(['idCategory' => $model->idCategory]);
        foreach ($cfs as $cf) {
            if (isset($model[$cf->alias])) {
                $attributes[] = ['label' => $cf->title, 'value'=>$model[$cf->alias]];
            }
        }
    }
    if ($model->images) {
        foreach ($model->images as $img) {
            /** @noinspection PhpUndefinedFieldInspection */
            $image = \Yii::$app->files->getUrl($model, 'images', null, $img);
            echo Html::a(
                Html::img($image, ['class' => 'small', 'width' => 64]),
                $image,
                ['class' => 'fancybox', 'data-fancybox-group' => 'gallery', 'title' => '']
            );
        }
    }
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]);
    ?>
</div>
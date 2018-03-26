<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 * @var string $message
 * @var \common\models\QuestionConversation[] $conversations
 */

use common\models\Business;
use frontend\extensions\MyStarRating\MyStarRating;
use kartik\widgets\Alert;
use office\extensions\BlockBusinessOrder\BlockBusinessOrder;
use office\extensions\BlockBusinessQuestion\BlockBusinessQuestion;
use office\extensions\BlockBusinessView\BlockBusinessView;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank', 'style' => 'margin-left: 20px']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);

if ($message) {
    echo Alert::widget([
        'type' => Alert::TYPE_SUCCESS,
        'body' => $message,
    ]);
}
?>

<div class="business-view">
    <?= $this->render('top_block', ['id' => $model->id, 'active' => 'view']) ?>

    <?= DetailView::widget([
        'model' => $model,
        'options' => [
            'class' => 'table table-striped table-bordered detail-view business-detail-view',
        ],
        'attributes' => [
            'id',
            'title',
            'short_url',
            [
                'attribute' => 'idProductCategories',
                'type' => 'raw',
                'value' => $model->productCategoryNames($model->idProductCategories),
            ],
            
            [
                'attribute' => 'idCity',
                'value' => ($model->idCity)? $model->city->title : '',
            ],
            [
                'attribute' => 'image',
                'format' => 'image',
                'value' => $model->image ? Yii::$app->files->getUrl($model, 'image', 65) : null,
            ],
            [
                'format' => 'html',
                'attribute' => 'price_type',
                'label' => 'Тариф',
                'value' => Business::$priceTypes[$model->price_type].'<span class="tariff-desc">Для смены тарифа свяжитесь с саппортом</span>',
//                'options' => ['width' => '120px'],
            ],
            [
                'format' => 'html',
                'label' => 'Статус оплаты',
                'value' => $model->getStatusPaid(),
            ],
            [
                'attribute' => 'dateCreate',
                'label' => 'Дата оплаты',
                'value' => $model->getDueDate(),
            ],
        ],
    ]) ?>
</div>

<div class="row mt-50">
    <div class="col-lg-3 col-xs-6">
        <?= BlockBusinessView::widget(['business' => $model]) ?>
    </div>

    <div class="col-lg-3 col-xs-6">
        <?= BlockBusinessQuestion::widget(['business' => $model]) ?>
    </div>

    <div class="col-lg-3 col-xs-6">
        <?= BlockBusinessOrder::widget(['business' => $model]) ?>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box btn-success">
            <div class="inner">
                <h3><?= MyStarRating::widget(['id' => $model->id, 'rating' => $model->rating, 'readOnly' => true])  ?></h3>

                <p>Рейтинг</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer"  style="cursor:default;" onclick="return false">
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>
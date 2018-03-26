<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */

use common\extensions\ViewCounter\BusinessViewCounter;
use common\models\Business;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Businesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank']);

$script = <<< JS
    $('.content .business-view p.list-btn').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="business-view">

    <p class="list-btn">
        <?= Html::a(Yii::t('business', 'Btn_update_a'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('business', 'Btn_delete_a'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('business', 'Are_you_sure_you_want_to_delete_this_item'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('business', 'Btn_create'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('business', 'Btn_index_a'), ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
    $attributes = [
        'id',
        'title',
        [
            'attribute' => 'type',
            'value' => $model->type ? Business::$types[$model->type] : '',
        ],
        'ratio',
        [
            'attribute' => 'seo_title',
            'value' => (string)$model->seo_title,
        ],
        'seo_description',
        'seo_keywords',
        [
            'attribute' => 'sitemap_en',
            'value' => $model->sitemap_en ? 'да' : 'нет',
        ],
        [
            'attribute' => 'sitemap_priority',
            'value' => (string)$model->sitemap_priority,
        ],
        [
            'attribute' => 'sitemap_changefreq',
            'value' => (string)$model->sitemap_changefreq,
        ],
        'url',
        'short_url',
        'idUser',
        [
            'attribute' => 'idCategories',
            'type' => 'raw',
            'value' => $model->categoryNames($model->idCategories),
        ],
        [
            'attribute' => 'idProductCategories',
            'type' => 'raw',
            'value' => $model->productCategoryNames($model->idProductCategories),
        ],
        [
            'attribute' => 'idCity',
            'value' => $model->city ? $model->city->title : ''
        ],
        'description:html',
        'site',
        'phone',
        'urlVK:url',
        'urlFB:url',
        'urlTwitter:url',
        'email:email',
        'skype',
        'image',
        'background_image',
        [
            'label' => 'Дата создания',
            'value' => date('d.m.Y, H:i:s', strtotime($model->dateCreate)),
        ],
        'due_date:date',
        'tags',
        [
            'label' => 'Рейтинг пользователей',
            'value' => $model->rating
        ],
        [
            'label' => 'Всего просмотров',
            'value' => BusinessViewCounter::widget(['item' => $model->id, 'count' => false])
        ],
        [
            'label' => 'Шаблон',
            'format' => 'raw',
            'value' => $model->template ? ($model->template->title . '<br>' . Html::img(Yii::$app->files->getUrl($model->template, 'img', 100))) : ''
        ],
    ];

    $configCF = [['label' => '', 'value' => '<p style="color: #0daa04; font-weight: 900;">Кастомфилды</p>', 'format' => 'html']];
    $already = [];
    foreach ($model->businessCategories as $businessCategory) {
        $configCF[] = ['label' => $businessCategory->title, 'value' => ''];
        foreach ($businessCategory->customFields as $field) {
            if (in_array($field->id, $already)) {
                continue;
            }
            $already[] = $field->id;
            $arrayOfVal = [];
            foreach ($field->getCustomFieldValues($model->id) as $valueObj) {
                $arrayOfVal[] = $valueObj->anyValue;
            }
            $configCF[] = ['label' => $field->title, 'value' => implode(', ', $arrayOfVal)];
        }
    }

    $attributes = array_merge($attributes, $configCF);
    $config = [
        'model' => $model,
        'attributes' => $attributes,
    ]
    ?>
    <?= DetailView::widget($config) ?>
</div>
<?php

use common\models\City;
use common\models\KinoGenre;
use common\models\Wall;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Afisha;
use kartik\widgets\Select2;
use common\models\File;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Afisha */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фильмы';
$this->params['breadcrumbs'][] = $this->title;

$all_cities_ge = City::find()->select(['id', 'title_ge'])->where(['id' => Yii::$app->params['activeCitysBackend']])->all();
$all_cities_ge = ArrayHelper::map($all_cities_ge, 'id', 'title_ge');
?>
<div class="afisha-index">


    <p>
        <?= Html::a('Добавить фильм', ['create', 'isFilm'=>true], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Расписание фильмов', ['schedule-kino/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'100px'],
                'filter' => false,
                'value' => function ($model) {
                    if($model->image){
                        return '<img  width="100"  src=' . \Yii::$app->files->getUrl($model, 'image', 70) . ' " >';
                    }
                    else return '';
                },
            ],
            [
                'attribute' => 'genre',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return $model->genres ? implode(', ', ArrayHelper::getColumn($model->genres, 'title')) : null;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => KinoGenre::getAll(),
                    'attribute' => 'genre',
                    'options' => [
                        'placeholder' => 'Select a genre ...',
                        'id' => 'genre',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'isChecked',
                'options' => ['width'=>'90px'],
                'value' => function($model){
                    return ($model->isChecked) ? 'Да' : 'Нет';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [false => 'Нет', true => 'Да'],
                    'attribute' => 'isChecked',
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'year',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return ($model->year)? $model->year : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Afisha::yearList(),
                    'attribute' => 'year',
                    'options' => [
                        'placeholder' => 'Select a year ...',
                        'id' => 'year',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            'order',
            [
                'label' => '',
                'value' => function (Afisha $model) use ($all_cities_ge) {
                    $getLabel = function ($publish_status) {
                        switch ($publish_status) {
                            case 'not_published':
                                $text = '<span class="label label-info">Нет группы в ВК</span>';
                                break;
                            case null:
                                $text = '<span class="label label-warning">Публикуется</span>';
                                break;
                            case 1:
                                $text = '<span class="label label-success">Опубликовано</span>';
                                break;
                            default:
                                $text = '<span class="label label-default">Error</span>';
                        }
                        return $text;
                    };

                    $text = [];
                    $city_id = Yii::$app->request->cookies->getValue('SUBDOMAINID', null);
                    $published_cities = City::find()->select('id')->where(['not', ['vk_public_id' => null]])->andWhere(['not', ['vk_public_admin_token' => null]])->andWhere(['not', ['vk_public_admin_id' => null]])->column();
                    if (!is_null($city_id) and !in_array($city_id, $published_cities)) {
                        return "{$getLabel('not_published')} для {$all_cities_ge[$city_id]}";
                    }
                    /** @var Wall[] $wall */
                    $wall = Wall::find()->where(['pid' => $model->id, 'type' => File::TYPE_AFISHA_WITHOUT_SCHEDULE])->andFilterWhere(['idCity' => $city_id])->all();

                    if ($wall) {
                        foreach ($wall as $item) {
                            $text[] = "Для {$item->city->title_ge}{$getLabel($item->published)}<br>";
                        }
                    } else {
                        $activeCities = is_null($city_id) ? $published_cities : [$city_id];

                        foreach ($activeCities as &$city) {
                            $city = $all_cities_ge[$city];
                        }
                        $activeCities = implode(', ', $activeCities);

                        $text[] = Html::a('Опубликовать', ['/afisha/publish-kino', 'id' => $model->id, 'city' => $city_id], ['class' => 'btn btn-danger']);
                        $text[] = "<br>для {$activeCities}";
                    }
                    return implode($text);
                },
                'format' => 'html',
                'options' => ['width'=>'230px']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px']
            ],
        ],
    ]); ?>

</div>

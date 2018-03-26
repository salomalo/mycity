<?php
/**
 * @var $this \yii\web\View
 * @var $business \common\models\Business
 * @var $model common\models\Comment
 * @var $countComments integer
 * @var $lvlGoodComment integer
 * @var $countCommentPrice integer
 * @var $countGoodCommentPrice integer
 * @var $countProdAvailability integer
 * @var $countGoodProdAvailability integer
 * @var $countCorrectDescription integer
 * @var $countGoodCorrectDescription integer
 * @var $countInTime integer
 * @var $countGoodInTime integer
 * @var $countCallback integer
 * @var $countGoodCallback integer
 */

use common\models\Comment;
use common\models\File;
use common\models\Lang;
use frontend\extensions\CommentStat\CommentStat;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

$alias = "{$business->id}-{$business->url}";
$city = Yii::$app->request->city;


$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);

$breadcrumbs = [
    ['label' => $business->title, 'url' => Url::to(['/business/view', 'alias' => $alias])],
    ['label' => 'Отзывы о компании ' . $business->title, 'url' => Url::to(['/comment/list', 'idBusiness' => $business->id])],
    ['label' => 'Добавить отзыв'],
];

$this->title = 'Добавить отзыв о компании ' . $business->title;

$lvlGoodPrice = $countCommentPrice ? (integer)($countGoodCommentPrice / $countCommentPrice * 100) : 0;
$lvlGoodProdAvailability = $countProdAvailability ? (integer)($countGoodProdAvailability / $countProdAvailability * 100) : 0;
$lvlGoodDescription = $countCorrectDescription ? (integer)($countGoodCorrectDescription / $countCorrectDescription * 100) : 0;
$lvlGoodInTime = $countInTime ? (integer)($countGoodInTime / $countInTime * 100) : 0;
$lvlGoodCallback = $countCallback ? (integer)($countGoodCallback / $countCallback * 100) : 0;
?>

<div class="main-inner">
    <div class="container">
        <div class="row" style="background-color: #fff;margin-top: 20px;margin-bottom: 50px">
            <?= Breadcrumbs::widget([
                'tag' => 'ol',
                'options' => ['class' => 'breadcrumb'],
                'homeLink' => ['label' => $homeTitle, 'url' => $main],
                'links' => $breadcrumbs,
            ]); ?>
            <h1>Добавить отзыв о компании <?= Html::a($business->title, Url::to(['/business/view', 'alias' => $alias]), ['target' => '_blank', 'title' => $business->title]) ?></h1>
            <div class="col-lg-7">
                <div class="comment-form-create">

                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'idUser')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false) ?>

                    <?= $form->field($model, 'type')->hiddenInput(['value' => File::TYPE_BUSINESS])->label(false) ?>

                    <?= $form->field($model, 'pid')->hiddenInput(['value' => $business->id])->label(false) ?>

                    <?= $form->field($model, 'rating_business')->radioList(Comment::$typesRatingBusiness, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $check = $checked ? ' checked="checked"' : '';
                            return '<div class="b-rating__item">
                            <input id="rating_' . $value . '" name="' . $name . '" value="' . $value . '" class="b-rating__input" type="radio"' . $check . '>
                            <label class="b-rating__label" for="rating_' . $value . '">
                                <div class="b-rating__icon icon-rating_' . $value . ' h-mb-5" data-qaid="icon-rating"></div>
                                <div class="b-rating__level">' . Comment::$typesRatingBusiness[$value] . '</div>
                                <div class="b-rating__tick"></div>
                            </label>
                        </div>';
                        }]);
                    ?>

                    <?= $form->field($model, 'business_type')->hiddenInput(['value' => Comment::TYPE_COMMENT_SHOP])->label(false) ?>

                    <?= $form->field($model, 'correct_price')->radioList(Comment::$typesRatingProperty)->label('Цена товара (услуги) была указана правильно?') ?>

                    <?= $form->field($model, 'product_availability')->radioList(Comment::$typesRatingProperty)->label('Наличие товара (услуги) было указано правильно?') ?>

                    <?= $form->field($model, 'correct_description')->radioList(Comment::$typesRatingProperty)->label('Описание товара (услуги) было актуальным?') ?>

                    <?= $form->field($model, 'order_executed_on_time')->radioList(Comment::$typesRatingProperty)->label('Заказ был выполнен в оговоренные сроки?') ?>

                    <?= $form->field($model, 'rating_callback', [
                        'template' => "{label}<div class=\"h-mb-10 h-font-size-12\">
                                                   При заказе в нерабочее время – отсчитывая от начала следующего рабочего дня.
                                              </div>\n{input}\n{hint}\n{error}"
                    ])->widget(Select2::className(), [
                        'model' => new \common\models\search\BusinessCategory(),
                        'data' => Comment::$typesCallback,
                        'attribute' => 'rating_callback',
                        'options' => [
                            'multiple' => false,
                        ],
                        'pluginOptions' => [
                            'allowClear' => false,
                        ]
                    ])->label('Как быстро с вами связались?') ?>

                    <?= $form->field($model, 'rating_recommend')->radioList(Comment::$typesRecommend)->label('Вы бы порекомендовали компанию своим друзьям?') ?>

                    <?= $form->field($model, 'text')->textarea(['rows' => 6])->label("Отзыв:") ?>
                    <span class="b-text-hint h-mb-20">
                        Осталось:
                            <span class="b-text-hint__length-counter" data-maxlength-max="500">500</span> символа(ов).
                    </span>

                    <button class="b-button b-button_theme_blue h-inline-block h-vertical-middle" type="submit">
                        <i class="icon-add_comment_but h-vertical-middle h-mr-5 h-ml-15"></i>
                        <span class="b-button__aligner"></span>
                        <span class="b-button__text h-font-size-14 h-mr-15 h-ml-10">Добавить отзыв</span>
                    </button>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <div class="col-lg-5">
                <?php if ($countComments) : ?>
                    <div class="b-grids__item h-width-300">
                    <div class="h-mb-20">
                        <div class="b-reviews-stat h-vertical-middle b-reviews-stat_with_border b-reviews-stat_layout_table h-mb-20">
                            <div class="b-reviews-stat__column b-reviews-stat__column_theme_blue qa-opinion-count">
                                <span class="b-reviews-stat__value h-text-center "><?= $countComments ?></span>
                                <span class="b-reviews-stat__description h-text-center">отзыва</span>
                                <span class="b-reviews-stat__corner"></span>
                            </div>
                            <div class="b-reviews-stat__column ">
                                <span class="b-reviews-stat__value h-text-center"><?= $lvlGoodComment ?>%</span>
                                <span class="b-reviews-stat__description">положительных
                            </span>
                            </div>
                        </div>
                        <?= CommentStat::widget(['business' => $business]) ?>
                    </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

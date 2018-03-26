<?php
use common\models\Lang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\PasswordResetRequestForm $model
 */
$this->title = Yii::t('app', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;

$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
$main_string = ($canonical === Url::to($cur_lang, true)) ? null : '/';
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="reklama-title">
                            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                                <?= Breadcrumbs::widget([
                                    'tag' => 'ol',
                                    'options' => ['class' => 'breadcrumb'],
                                    'homeLink' => ['label' => $homeTitle, 'url' => $main],
                                    'links' => $breadcrumbs,
                                ]); ?>
                            <?php else : ?>
                                <?= Html::ul([['label' => Yii::t('app', 'Home'), 'url' => $main_string]], ['item' => function ($item, $index) {
                                    if (!empty($item['url'])) {
                                        return Html::tag('li', Html::a($item['label'], [$item['url']], []), ['class' => false]);
                                    } else {
                                        return Html::tag('li', $item['label'], ['class' => false]);
                                    }
                                }, 'class' => 'breadcrumb']); ?>
                            <?php endif; ?>
                            <div class="modal-header our-modal-header">
                                <h1><?= Yii::t('app', 'Request password reset') ?></h1>
                            </div>

                            <div class="modal-body our-modal-body">
                                <div class="panel-body">
                                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                                    <?= $form->field($model, 'email') ?>
                                    <div class="form-group">
                                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>

                        </div><!-- /.document-title -->

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
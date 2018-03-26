<?php
/**
 * @var $this \yii\web\View
 * @var $charts array
 */
use common\extensions\ChartsWidget;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('detail-log', 'Charts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('detail-log', 'Detail Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detail-log-index">

    <div>
        <p style="float: left; margin-right: 20px;">
            <?= Html::a(Yii::t('detail-log', 'Index'), ['index'], ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('detail-log', 'charts'), ['charts'], ['class' => 'btn btn-success']) ?>
        </p>

        <div>
            <?= Html::beginForm(Url::to(['detail-log/charts-for-period']), 'get', ['class' => 'form-inline form-with-disabling-submit']) ?>
                <div class="form-group">
                    <?= Html::label('Период', 'startInput', ['class' => 'sr-only']) ?>
                    <?= DatePicker::widget([
                        'name' => 'start',
                        'value' => Yii::$app->request->get('start', date('Y-01-01')),
                        'options' => ['id' => 'startInput', 'placeholder' => 'Выберите начало', 'class' => 'form-control'],
                        'convertFormat' => true,
                        'pluginOptions' => ['allowClear' => true, 'format' => 'yyyy-MM-dd']
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= Html::label('Период', 'endInput', ['class' => 'sr-only']) ?>
                    <?= DatePicker::widget([
                        'name' => 'end',
                        'value' => Yii::$app->request->get('end', date('Y-m-d')),
                        'options' => ['id' => 'endInput', 'placeholder' => 'Выберите окончание', 'class' => 'form-control'],
                        'convertFormat' => true,
                        'pluginOptions' => ['allowClear' => true, 'format' => 'yyyy-MM-dd']
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= Html::label('Период', 'periodInput', ['class' => 'sr-only']) ?>
                    <?= Html::dropDownList('period', Yii::$app->request->get('period', 'M'), [
                        'D' => 'День', 'W' => 'Неделя', 'M' => 'Месяц', 'HY' => 'Полгода', 'Y' => 'Год'
                    ], ['id' => 'periodInput', 'class' => 'form-control']) ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Применить', ['class' => 'btn btn-primary', 'id' => 'filter_submit_button']) ?>
                </div>
            <?= Html::endForm() ?>
        </div>
    </div>
    <div class="row" style="clear: both">
        <?php foreach ($charts as $chart) : ?>
            <div class="col-lg-6"">
                <div class="panel">
                    <?= ChartsWidget::widget($chart); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
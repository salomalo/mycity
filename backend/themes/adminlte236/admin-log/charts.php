<?php
/**
 * @var $this \yii\web\View
 * @var $charts array
 */
use common\extensions\ChartsWidget;
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
            <?= Html::a(Yii::t('detail-log', 'charts-for-period'), ['charts-for-period'], ['class' => 'btn btn-success']) ?>
        </p>

        <div>
            <?= Html::beginForm(Url::to(['admin-log/charts']), 'get', ['class' => 'form-inline form-with-disabling-submit']) ?>
                <div class="form-group">
                    <?= Html::label('Период', 'periodInput', ['class' => 'sr-only']) ?>
                    <?= Html::dropDownList('period', Yii::$app->request->get('period', 'M'), [
                        'D' => 'День', 'W' => 'Неделя', 'M' => 'Месяц', 'HY' => 'Полгода', 'Y' => 'Год'
                    ], ['id' => 'periodInput', 'class' => 'form-control']) ?>
                </div>
                <div class="form-group">
                    <?= Html::label('Длительность', 'durationInput', ['class' => 'sr-only']) ?>
                    <?= Html::dropDownList('duration', Yii::$app->request->get('duration', 6), [
                        1 => '1', 3 => '3', 6 => '6', 9=> '9', 12 => '12',
                        15 => '15', 18 => '18', 24 => '24', 30 => '30', 60 => '60'
                    ], ['id' => 'durationInput', 'class' => 'form-control']) ?>
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
<?php
/**
 * @var yii\web\View $this
 * @var array $charts
 */
use common\extensions\ChartsWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-lg-12">
        <!--        <ol class="breadcrumb">-->
        <!--            <li class=""><i class="fa fa-dashboard"></i> Dashboard</li>-->
        <!--            <li class="active"><i class="fa fa-dashboard"></i> vefver</li>-->
        <!--        </ol>-->
        <!--        <div class="alert alert-success alert-dismissable">-->
        <!--            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
        <!--            Welcome to SB Admin by <a class="alert-link" href="http://startbootstrap.com">Start Bootstrap</a>! Feel free to use this template for your admin needs! We are using a few different plugins to handle the dynamic tables and charts, so make sure you check out the necessary documentation links provided.-->
        <!--        </div>-->
    </div>
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">456</p>
                        <p class="announcement-text">New Mentions!</p>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer announcement-bottom">
                    <div class="row">
                        <div class="col-xs-6">
                            View Mentions
                        </div>
                        <div class="col-xs-6 text-right">
                            <i class="fa fa-arrow-circle-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">12</p>
                        <p class="announcement-text">To-Do Items</p>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer announcement-bottom">
                    <div class="row">
                        <div class="col-xs-6">
                            Complete Tasks
                        </div>
                        <div class="col-xs-6 text-right">
                            <i class="fa fa-arrow-circle-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">18</p>
                        <p class="announcement-text">Crawl Errors</p>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer announcement-bottom">
                    <div class="row">
                        <div class="col-xs-6">
                            Fix Issues
                        </div>
                        <div class="col-xs-6 text-right">
                            <i class="fa fa-arrow-circle-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">56</p>
                        <p class="announcement-text">New Orders!</p>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer announcement-bottom">
                    <div class="row">
                        <div class="col-xs-6">
                            Complete Orders
                        </div>
                        <div class="col-xs-6 text-right">
                            <i class="fa fa-arrow-circle-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div><!-- /.row -->

<div style="text-align: right;">
    <?= Html::beginForm(['/'], 'post', ['class' => 'form-inline form-with-disabling-submit']) ?>
    <div class="form-group">
        <?= Html::label('Период', 'periodInput', ['class' => 'sr-only']) ?>
        <?= Html::dropDownList('period', Yii::$app->request->get('period', 'M'), [
            'D' => 'День', 'W' => 'Неделя', 'M' => 'Месяц'
        ], ['id' => 'periodInput', 'class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Применить', ['class' => 'btn btn-primary', 'id' => 'filter_submit_button']) ?>
    </div>
    <?= Html::endForm() ?>
</div>

<div class="row">
    <?php foreach ($charts as $chart) : ?>
        <div class="col-lg-12 widget-chart-js" data-url="<?= Url::to(['/admin-log/charts']) ?>">
            <div class="panel">
                <?= ChartsWidget::widget($chart); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
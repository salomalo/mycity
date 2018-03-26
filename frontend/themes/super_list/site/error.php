<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\SignupForm $model
 */
$this->title = '404 page';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main">
    <div class="main-inner">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div id="primary">

                        <section class="no-results not-found">
                            <div class="page-content">
                                <div class="number">
                                    404

                                    <div class="number-description"><?= Yii::t('app', 'Page does not exist') ?></div><!-- /.number-description -->
                                </div><!-- /.number -->
                            </div><!-- .page-content -->
                        </section><!-- .no-results -->

                    </div><!-- /#primary -->
                </div><!-- /.col-* -->
            </div><!-- /.row -->

          </div><!-- /.content -->
    </div><!-- /.main-inner -->
</div>

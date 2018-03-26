<?php
/**
 * @var yii\web\View $this
 * @var $tariff integer
 */

use common\models\Business;
use yii\helpers\Url;

$this->title = 'Типы предприятия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Выберите тип предприятия</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?= Url::to(['/business/create', 'tariff' => $tariff, 'businessType' => Business::TYPE_SHOP]) ?>">
                            <button type="button" class="btn btn-block btn-primary">Интернет магазин</button>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= Url::to(['/business/create', 'tariff' => $tariff, 'businessType' => Business::TYPE_OTHER]) ?>">
                            <button type="button" class="btn btn-block btn-primary">Обычное</button>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

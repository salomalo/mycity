<?php

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Логи Админов';
$this->params['breadcrumbs'][] = $this->title;

$js = "$(document).ready(function(){ 
            $('table.table').after($('ul.pagination'));
        });";

$this->registerJs($js);
?>
<div class="action-index">

<ul class="nav nav-tabs">
    <li>
        <?= Html::a('Все логи', ['admin-log/index']) ?>
    </li>    
    <li class="<?= ($trunc == 'day')? 'active' : false ?>">
        <?= Html::a('Ежедневный отчет', ['admin-log/trunc', 'trunc' => 'day']) ?>
    </li>
    <li class="<?= ($trunc == 'week')? 'active' : false ?>">
        <?= Html::a('Недельный отчёт', ['admin-log/trunc', 'trunc' => 'week']) ?>
    </li>
    <li class="<?= ($trunc == 'month')? 'active' : false ?>">
        <?= Html::a('Месячный отчёт', ['admin-log/trunc', 'trunc' => 'month']) ?>
    </li>
</ul>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Дата</th>
                <?php foreach ($this->context->listUser as $user):?>
                    <th><?= $user?></th>
                <?php endforeach;?>
            </tr>
        </thead>
        
        <tbody>
            <?php
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
//                    'layout' => "\n{items}\n{pager}",
//                    'itemView' => ($trunc == 'day')? '_log_short' : '_log_short_week',    
                    'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_log_short',['model' => $model, 'key' => $key,]);
                    }, 
                    'itemOptions' => ['class' => 'item'],
                    /*'itemOptions' => [
                        'class' => 'bg-info',
                        'tag' => 'article'
                    ]*/
                ]);
            ?>
        </tbody>
    </table>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Ticket;
use yii\widgets\ActiveForm;

$this->title =  Yii::t('ticket', 'Ticket') . ' '.$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('ticket', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-view">

    <p>
        <?= Html::a(Yii::t('ticket', 'Close'), ['close', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to close this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-success">
        <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="fa fa-comments-o"></i>
        <h3 class="box-title"><?= Yii::t('ticket', 'Chat')?></h3>
        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
            <div class="btn-group" data-toggle="btn-toggle">
                <button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
            </div>
        </div>
        </div>
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
        <div class="box-body chat" id="chat-box" style="overflow: hidden; width: auto;">
            <!-- chat item -->
            <?php  foreach ($history as $item): ?>
            <div class="item">
                <?php if (!empty($item->user->photoUrl)): ?> 
                <img src="<?=\Yii::$app->files->getUrl($item->user,'photoUrl',100) ?>" alt="user image" class="online">
                <?php else: ?>
                <img src="/img/avatar04.png" alt="user image" class="online">                    
                <?php endif ?>
                <p class="message">
                <a href="#" class="name">
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?=$item->dateCreate?></small>
                    <?=$item->user->username ?>
                </a>
                <?=Html::encode($item->body);?>
                </p>
            </div><!-- /.item -->
            <?php endforeach; ?>
            <!-- chat item -->                                   
        </div>
            <div class="slimScrollBar" style="width: 7px; position: absolute; top: 25px; opacity: 0.4; display: none; border-radius: 0px; z-index: 99; right: 1px; height: 224.820143884892px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);">
                
            </div>                
        </div>
        <!-- /.chat -->
        <div class="box-footer">
        <?php
            $form = ActiveForm::begin();
        ?>
        <div class="input-group">
            <?=
            $form->field($model_history, 'body', [
            'template' => "{input}\n",
            'inputOptions' => ['placeholder' => 'Введите сообщение...', 'class' => 'form-control']
            ]
            )
            ?>    
            <div class="input-group-btn">
                <?= Html::submitButton('<i class="fa fa-plus"></i>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

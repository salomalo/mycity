<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\User;
use common\models\Ticket;
/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

   <?php // $form->field($model, 'title')->textInput(['maxlength' => 255, 'readonly'=>'readonly']) ?>

        <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => ArrayHelper::map(User::find()->all(),'id','username'),
        'options' => [
            'placeholder' => 'Выберите пользователя   ...',
            'id' => 'idUser',
            'multiple' => false ,
        ],
        'pluginOptions' => ['allowClear' => true],
    ])->label("Пользователь");?>
        <?= $form->field($model, 'type')->widget(Select2::className(),[
        'data' => Ticket::$types,
        'options' => [
            'placeholder' => 'Выберите тип запроса ...',
            'id' => 'type',
        ],
        'pluginOptions' => ['allowClear' => true],
        ])->label("Тип запроса"); 
    ?>
    
    <?php if($model->type==Ticket::TYPE_COMPANY):?>
        <?= $form->field($model, 'pid')->textInput()->label("Компания"); ?>
    <?php endif;?>
    
    <?= $form->field($model, 'title')->textInput()->label("Заголовок тикета"); ?>
    
    <?php if ($model->isNewRecord):?>
        <?= $form->field($model, 'body')->textInput()->label("Содержание"); ?>
    <?php endif;?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    
    
    <div class="box box-success">
        <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="fa fa-comments-o"></i>
        <h3 class="box-title">Чат</h3>
        </div>
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
        <div class="box-body chat" id="chat-box" style="overflow: hidden; width: auto;">
            <!-- chat item -->
            <?php foreach ($history as $item) : ?>
                <div class="item">
                    <?php if (!empty($item->user->photoUrl)): ?>
                    <img src="<?=\Yii::$app->files->getUrl($item->user,'photoUrl',100) ?>" alt="user image" class="online">
                    <?php else: ?>
                    <img src="/img/avatar04.png" alt="user image" class="online">
                    <?php endif ?>
                    <p class="message">
                    <a href="#" class="name">
                        <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?=$item->dateCreate?></small>
                        <?= ($model->user)? $model->user->username : 'Гость' ?>
                    </a>
                    <?=Html::encode($item->body);?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
            <div class="slimScrollBar" style="width: 7px; position: absolute; top: 25px; opacity: 0.4; display: none; border-radius: 0px; z-index: 99; right: 1px; height: 224.820143884892px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);">
                
            </div>                
        </div>
    </div>
</div>

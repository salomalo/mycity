<?php

use backend\models\AdminComment;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Lead */
/* @var $model common\models\Lead */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Leads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lead-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info', 'style' => 'width:63px']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'label' => 'Статус',
                'value' => $model->getStatusHtml(),
                'format' => 'raw',
            ],
            'phone',
            'description',
            'date_create',
            'utm_source',
            'utm_campaign',
        ],
    ]) ?>

    <?php
    /** @var AdminComment[] $adminComments */
    $adminComments = AdminComment::find()
        ->where(['type' => AdminComment::TYPE_LEAD, 'object_id' => $model->id])
        ->all();
    ?>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <!-- Box Comment -->
                <div class="box box-widget">
                    <div class="box-header with-border" style="height: 40px;">
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer box-comments">
                        <?php foreach ($adminComments as $comment) : ?>
                            <div class="box-comment">
                                <!-- User image -->
                                <img class="img-circle img-sm"
                                     src="/img/avatar04.png"
                                     alt="User Image">

                                <div class="comment-text">
                                <span class="username">
                                    <?= $comment->admin->username ?>
                                    <span class="text-muted pull-right"><?= $comment->date_create ?>
                                    </span>
                                </span>
                                    <?= nl2br($comment->text) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- /.box-comment -->
                    </div>
                    <!-- /.box-footer -->
                    <div class="box-footer">
                        <?php $formComment = ActiveForm::begin(['action' => '/admin-comment/create', 'id' => 'forum_admin_comment', 'method' => 'post',]); ?>
                        <?= $formComment->field($modelAdminComment, 'text', [
                            'template' => '<img class="img-responsive img-circle img-sm" src="/img/avatar04.png" alt="Alt Text">
                        <div class="img-push">
                            {input}
                        </div>'
                        ])
                            ->textarea(['placeholder' => 'Press enter to post comment', 'rows' => 4])
                            ->label(false) ?>

                        <?= $formComment->field($modelAdminComment, 'admin_id')->hiddenInput(['value' => Yii::$app->user->getIdentity()->id])->label(false) ?>

                        <?= $formComment->field($modelAdminComment, 'object_id')->hiddenInput(['value' => $model->id])->label(false) ?>

                        <?= $formComment->field($modelAdminComment, 'type')->hiddenInput(['value' => AdminComment::TYPE_LEAD])->label(false) ?>
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>

</div>

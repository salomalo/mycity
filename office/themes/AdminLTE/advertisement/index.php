<?php
/**
 * @var $this yii\web\View
 * @var $models \common\models\Advertisement[]
 */

use yii\helpers\Html;

$this->title = Yii::t('advertisement', 'Advertisements');
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a(Yii::t('advertisement', 'Create'), ['select'], ['class' => 'btn btn-success']) ?>
</p>

<div class="row">
    <div class="col-xs-12">
        <div class="box grid-view-border-top">
            <div class="box-header">
                <h3 class="box-title"><?= Yii::t('advertisement', 'Advertisements') ?></h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th><?= Yii::t('advertisement', 'Title') ?></th>
                        <th><?= Yii::t('advertisement', 'Image') ?></th>
                        <th><?= Yii::t('advertisement', 'Status') ?></th>
                        <th><?= Yii::t('advertisement', 'Start date') ?></th>
                        <th><?= Yii::t('advertisement', 'Actions') ?></th>
                    </tr>
                    <?php foreach ($models as $model) : ?>
                        <tr>
                            <td><?= $model->id ?></td>
                            <td><?= $model->title ?></td>
                            <td><?= Html::img(Yii::$app->files->getUrl($model, 'image'), ['style' => 'max-height: 50px']) ?></td>
                            <td><?= $model->timeStatusLabel ?></td>
                            <td><?= $model->date_start ?></td>
                            <td>
                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id]) ?>
                                <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'id' => $model->id], [
                                    'id' => 'post-button',
                                    'data' => ['confirm' => Yii::t('advertisement', 'Are you sure you want to delete this item?'), 'method' => 'post'],
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
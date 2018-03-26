<?php
/**
 * @var $this \yii\web\View
 * @var $providers \common\models\Provider[]
 */
$this->title = Yii::t('provider', 'Providers');
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>

    <div class="col-xs-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Yii::t('provider', 'Providers') ?></h3>

                <div class="box-tools">
                    <?=Html::a(Yii::t('provider', 'Create'), ['/provider/create'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>№</th>
                        <th><?= Yii::t('provider', 'Name') ?></th>
                        <th><?= Yii::t('provider', 'Description') ?></th>
                        <th></th>
                    </tr>

                    <?php foreach ($providers as $provider) : ?>
                        <tr>
                            <td><?= $provider->id ?></td>
                            <td><?= $provider->title ?></td>
                            <td><?= $provider->description ?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil-square fa-2x" aria-hidden="true"></i>', ['/provider/update', 'id' => $provider->id]) ?>
                                <?= Html::a('<i class="fa fa-bitbucket-square fa-2x" aria-hidden="true"></i>', ['/provider/delete', 'id' => $provider->id], [
                                    'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'post'],
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
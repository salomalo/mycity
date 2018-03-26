<?php
/**
 * @var \common\models\Ads[] $models
 */
use yii\helpers\Url;
use frontend\extensions\CustomfieldsList\CustomfieldsList;
use yii\helpers\Html;
use common\models\User;
?>

<?php foreach ($models as $model): ?>

<div class="tovar">
    <a href="<?= Url::to(['ads/view', 'alias'=>$model->url])?>" class="title-img">
        <?php if($model->image):?>
            <img src="<?= \Yii::$app->files->getUrl($model, 'image', 100) ?>" alt="" />
        <?php endif;?>
    </a>

    <div class="characters">
        <a href="<?= Url::to(['ads/view', 'alias'=>$model->_id . '-' . $model->url])?>" class="title"><?= $model->title ?>
        <?php if(!empty($model->tovar->model)):?>
        <?=$model->tovar->model ?></a>
        <?php endif; ?>
        <?php if (Yii::$app->user->identity):?>
            <?php if ($model->idUser == Yii::$app->user->identity->id or Yii::$app->user->identity->role == User::ROLE_EDITOR):?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/ads/update', 'alias' => $model->_id . '-' . $model->url]), [])?>
            <?php endif?>
        <?php endif;?>
        <ul class="parametres">
            <?= CustomfieldsList::widget([
                'model' => $model,
                'isFull' => false
            ]) ?>
        </ul>
        <span class="comm"><?= $model->getComments($model->_id)?></span>
        <span class="cat"><a href="<?= Url::to(['ads/index','pid'=>$model->category->url.''])?>"><?= $model->category->title ?></a></span>
    </div>
    <div class="price">
        <div class="money"><?= Yii::t('product', 'Price')?></div>
        <div class="cena inpredpr"><?= $model->price ?> грн.</div>
    </div>
</div>

<?php endforeach;?>

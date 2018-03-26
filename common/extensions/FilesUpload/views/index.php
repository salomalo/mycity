<?php
use yii\helpers\Html;

/**
 * @var \common\models\Ads $model
 */
?>
<label>Загрузка фотографий</label>
<ul class="edit_gallery_img" id="list">
    <?php foreach ($model->images as $img) : ?>
        <li>
            <?= Html::img(\Yii::$app->files->getUrl($model, 'images', null, $img)) ?>
            <span class="img_border"></span>

            <?= Html::tag('span', '', ['class' => 'img_border']) ?>

            <?= Html::a(
                Html::tag('i', '', ['class' => 'icon-close-mini-white']),
                ['/ads/delete-some-image'],
                [
                    'class' => 'btn btn-icon btn-red btn_remove_storefiles',
                    'data' => ['id' => $img]
                ]
            ); ?>
        </li>
    <?php endforeach; ?>
    <li class="add_img">
        <i class="fa fa-upload fa-2x" aria-hidden="true"></i>
        <?= Html::activeFileInput($model, 'images[]', ['class' => 'input_files', 'multiple'=>'multiple']) ?>
    </li>
</ul>
<br><br><br>
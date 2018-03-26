<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use common\models\ProductCustomfield;

?>

<div role="tabpanel" class="tab-pane" id="add-info">
    <?php if ($model->idCategory) : ?>
        <?php $cfs = ProductCustomfield::findAll(['idCategory' => $model->idCategory]); ?>
        <?php if (count($cfs) > 0) : ?>
            <?php foreach ($cfs as $cf) : ?>
                <?php if (!empty($model[$cf->alias]) and !empty($cf->title)) : ?>
                    <p><strong><?= $cf->title ?>:</strong></p>
                    <ul>
                        <li><?= $model[$cf->alias] ?></li>

                    </ul>
                    <p>&nbsp;</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
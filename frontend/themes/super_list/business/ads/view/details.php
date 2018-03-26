<?php
use common\models\ProductCustomfield;
use console\models\Arenda;

?>

<?php if ($model->idCategory === Arenda::getArendaId()) : ?>
    <?php $cfs = ProductCustomfield::findAll(['idCategory' => $model->idCategory]); ?>
    <?php if (count($cfs) > 0) : ?>
        <div class="listing-detail-section" id="listing-detail-section-detail">
            <h2 class="page-header"><?= Yii::t('ads', 'Detail') ?></h2>
            <div class="listing-detail-attributes">
                <ul>
                    <?php foreach ($cfs as $cf) : ?>
                        <?php if (!empty($model[$cf->alias]) and !empty($cf->title)) : ?>
                            <li>
                                <strong class="key"><?= $cf->title ?></strong>
                                <span class="value"><?= $model[$cf->alias] ?></span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div><!-- /.listing-detail-attributes -->
        </div>
    <?php endif; ?>
<?php endif; ?>


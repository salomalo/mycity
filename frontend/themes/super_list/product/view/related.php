<?php
use frontend\extensions\Related\Related;

?>
<div class="listing-detail-section" id="listing-detail-section-related">
    <h2 class="page-header"><?= Yii::t('product', 'Similar_products_from_the_category') ?></h2>
    <div class="listing-detail-attributes">
        <?= Related::widget([
            'title' => Yii::t('product', 'Similar_products_from_the_category'),
            'idCategory' => $model->idCategory,
            'idModel' => $model->_id,
            'model' => 'common\models\Product',
            'limit' => 4,
            'view' => 'product_super_list'
        ]); ?>
    </div>
</div>

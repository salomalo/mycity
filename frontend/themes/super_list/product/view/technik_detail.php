<?php
use frontend\extensions\CustomfieldsList\CustomfieldsList;

?>
<div class="listing-detail-section" id="listing-detail-section-technik-desc">
    <h2 class="page-header"><?= Yii::t('product', 'Detailed_Specifications') ?></h2>
    <div class="listing-detail-attributes my-list-attr">
        <?= CustomfieldsList::widget([
            'model' => $model,
            'template' => '_super_list',
        ]) ?>
    </div>
</div>
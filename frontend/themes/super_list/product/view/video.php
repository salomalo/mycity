<?php if ($model->video): ?>
    <div class="listing-detail-section" id="listing-detail-section-video">
        <h2 class="page-header"><?= Yii::t('product', 'Video_Gallery') ?></h2>
        <div class="listing-detail-attributes">
            <?php $arr = explode(',', $model->video); ?>
            <?php foreach ($arr as $item): ?>
                <iframe width="560" height="315" src="//www.youtube.com/embed/<?= $item ?>" frameborder="0"
                        allowfullscreen></iframe>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

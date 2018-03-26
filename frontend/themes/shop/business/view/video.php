<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */
?>

<div class="listing-detail-section" id="listing-detail-section-video">
    <h2 class="page-header">Видео</h2>
    <div class="video-embed-wrapper">
        <p>
            <iframe width="1280"
                    height="720"
                    src="https://www.youtube.com/embed/<?= $model->trailer ? $model->trailer : '' ?>">
            </iframe>
        </p>
    </div>
</div>
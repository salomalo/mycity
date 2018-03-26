<?php
use yii\helpers\Url;

?>

<?php if ($models = $model->getAds($model->_id)): ?>
    <div class="listing-detail-section" id="listing-detail-section-price">
    <h2 class="page-header"><?= Yii::t('product', 'Prices_in_shops') ?></h2>

    <?php //yii\helpers\BaseVarDumper::dump($models, 10, true); die();?>
    <?php foreach ($models as $item): ?>

    <div class="akcia price-tovar">

        <?php if ($item->business->image): ?>
        <a href="<?= Url::to(['business/view', 'id' => $item->business->id]) ?>" class="akcia-mini-img">
            <img src="<?= \Yii::$app->files->getUrl($item->business, 'image', 165) ?>" alt=""/>
        </a>
        <div class="akcia-mini-characters">

            <?php else: ?>
            <div class="akcia-mini-characters none-img">
                <?php endif; ?>
                <div>
                    <?php if ($item->business->image): ?>
                        <span class="diamond"></span>
                    <?php endif; ?>
                    <a href="<?= Url::to(['business/view', 'alias' => $item->business->url]) ?>"
                       class="title"><?= $item->business->title ?></a>
                </div>
                <div class="mesto_big">
                    <?= Yii::t('business', 'Address') ?>
                    <span><?= (!empty($item->business->address[0])) ? $item->business->address[0]['address'] : '' ?></span>
                </div>
                <div class="phone_big">
                    <?= Yii::t('business', 'Phone') ?> <span><?= $item->business->phone ?></span>
                </div>
                <div class="comm_big"><?= $item->business->getComments($item->business->id) ?></div>
            </div>
            <div class="price">
                <div class="pr-title"><?= Yii::t('product', 'Price') ?></div>
                <div class="pr-cena"><?= $item->price ?> грн.</div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
<?php endif; ?>
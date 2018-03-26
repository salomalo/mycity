<?php
/**
 * @var $models \common\models\Action[]
 */
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

?>
<div class="listing-detail-section" id="listing-detail-section-related">
    <h2 class="page-header"><?= Yii::t('action', 'Similar_shares_of_headings') ?></h2>
    <div class="listing-detail-attributes">
        <div class="full-akcia">
            <div class="block-content related-akcii">
                <?php $count = 0; ?>
                <?php foreach ($models as $item): ?>

                    <?php
                    $start = new DateTime($item->dateStart);
                    $end = new DateTime($item->dateEnd);
                    $alias = $item->id . '-' . $item->url;
                    $count++;

                    $actionClass = ($count % 2 == 0) ? 'ml-action-related' : '';
                    ?>
                    <div class="related-akcia <?= $actionClass ?>">
                        <a href="<?= Url::to(['action/view', 'alias' => $alias]) ?>" class="related-akcia-img">
                            <?php if ($item->image): ?>
                                <div class="action-div-image">
                                    <img src="<?= \Yii::$app->files->getUrl($item, 'image', 195) ?>"
                                         alt="<?= $item->title ?>"/>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="related-akcia-characters">
                            <a href="<?= Url::to(['action/view', 'alias' => $alias]) ?>"
                               class="related-akcia-title"><?= $item->title ?></a>
                            <a href="<?= Url::to(['action/index', 'pid' => $item->category->url]) ?>"
                               class="related-akcia-cat"><?= $item->category->title ?></a>
                            <?php if (isset($item->companyName)) : ?>
                                <div class="related-akcia-mesto">
                                    <?= Yii::t('action', 'Where_is_the') ?>
                                    <a href="<?= Url::to(['business/view', 'alias' => $item->companyName->id . '-' . $item->companyName->url]) ?>">
                                        <?= $item->companyName->title ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="related-akcia-time"><?= Yii::t('action', 'The_offer_is_valid') ?>
                                <span>с <?= $start->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></span></div>
                        </div>
                    </div>
                    <?php if ($count % 2 == 0) : ?>
                        <div style="clear: both; height: 1px; background-color: #e2e2e2" ?></div>
                    <?php endif ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

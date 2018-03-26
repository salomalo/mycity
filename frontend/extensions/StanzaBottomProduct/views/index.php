<?php
/**
 * @var $this \yii\web\View
 * @var $newProducts \common\models\Ads[]
 * @var $topRatingProducts \common\models\Ads[]
 * @var $featuredProducts \common\models\Ads[]
 * @var $onSaleProducts \common\models\Ads[]
 * @var $business \common\models\Business
 */
use yii\helpers\Url;

$aliasBusiness = "{$business->id}-{$business->url}";
?>

<div class="stripe">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="listingBox">
                    <h4 class="dashStyle2">Новые товары</h4>
                    <ul>
                        <?php foreach ($newProducts as $ads) :?>
                            <li>
                                <a href="<?= Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$ads->_id}-{$ads->url}"]) ?>">
                                    <span class="listingFrame">
                                        <img src="<?= Yii::$app->files->getUrl($ads, 'image')?>" width="74" height="84" alt="">
                                    </span>
                                    <div>
                                        <p>
                                            <?php if (isset($ads->category)) : ?>
                                                <strong class="listTitle"><?= $ads->category->title ?></strong>
                                            <?php endif; ?>
                                            <?= $ads->title ?>
                                        </p>
                                        <strong><?= $ads->price ?> грн.</strong>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!-- ( LISTING BOX END ) -->
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="listingBox">
                    <h4 class="dashStyle2">Toп товаров</h4>
                    <ul>
                        <?php foreach ($topRatingProducts as $ads) :?>
                            <li>
                                <a href="<?= Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$ads->_id}-{$ads->url}"]) ?>">
										<span class="listingFrame">
											<img src="<?= Yii::$app->files->getUrl($ads, 'image')?>" width="74" height="84" alt="">
										</span>
                                    <div>
                                        <p>
                                            <?php if (isset($ads->category)) : ?>
                                                <strong class="listTitle"><?= $ads->category->title ?></strong>
                                            <?php endif; ?>
                                            <?= $ads->title ?>
                                        </p>
                                        <div class="stars">
                                            <span class="starsimgRating"></span>
                                        </div><!-- ( STARS END ) -->
                                        <strong><?= $ads->price ?> грн.</strong>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!-- ( LISTING BOX END ) -->
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="listingBox">
                    <h4 class="dashStyle2">Топ просмотров</h4>
                    <ul>
                        <?php foreach ($featuredProducts as $ads) :?>
                            <li>
                                <a href="<?= Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$ads->_id}-{$ads->url}"]) ?>">
										<span class="listingFrame">
											<img src="<?= Yii::$app->files->getUrl($ads, 'image')?>" width="74" height="84" alt="">
										</span>
                                    <div>
                                        <p>
                                            <?php if (isset($ads->category)) : ?>
                                                <strong class="listTitle"><?= $ads->category->title ?></strong>
                                            <?php endif; ?>
                                            <?= $ads->title ?>
                                        </p>
                                        <strong><?= $ads->price ?> грн.</strong>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div><!-- ( LISTING BOX END ) -->
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="listingBox">
                    <h4 class="dashStyle2">Со скидкой</h4>
                    <ul>
                        <?php foreach ($onSaleProducts as $ads) :?>
                            <li>
                                <a href="<?= Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$ads->_id}-{$ads->url}"]) ?>">
										<span class="listingFrame">
											<img src="<?= Yii::$app->files->getUrl($ads, 'image')?>" width="74" height="84" alt="">
										</span>
                                    <div>
                                        <p>
                                            <?php if (isset($ads->category)) : ?>
                                                <strong class="listTitle"><?= $ads->category->title ?></strong>
                                            <?php endif; ?>
                                            <?= $ads->title ?>
                                        </p>
                                        <strong class="cl_e85200">
                                            <del><?= $ads->price ?> грн.</del>
                                            <?= $ads->price * (1 - $ads->discount / 100) ?> грн.</strong>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

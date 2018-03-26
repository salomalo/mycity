<?php
/**
 * @var $this \yii\web\View
 * @var $pid string
 */

use yii\helpers\Html;
use yii\helpers\Url;

$sort = Yii::$app->request->get('sort');

$order_sort = 'views';
$order = SORT_ASC;

if ($sort) {
    if (mb_substr($sort, 0, 1) === '-') {
        $order = SORT_DESC;
        $sort = mb_substr($sort, 1, mb_strlen($sort));
    }
    $order_sort = $sort;
}
?>

<div id="filter-3" class="widget widget_filter">
    <div class="widget-inner widget-pb">

        <?= Html::beginForm(['/search/action'], 'get', ['id' => 'find_action']) ?>

        <?= Html::hiddenInput('pid', $pid) ?>

        <div class="form-group form-group-keyword style-keyword-search">
            <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => Yii::t('business', 'Enter_search_phrase')]) ?>
        </div>

        <div class="form-group">
            <?= Html::input('submit', null, Yii::t('action', 'Search_btn'), ['style' => 'width: 266px']) ?>
        </div>

        <div class="filter-sorting-options clearfix">
            <div class="filter-sorting-inner">
                <div class="filter-sorting-inner-group filter-sorting-inner-group-types filter-sorting-types">
                    <strong><?= Yii::t('business', 'Sort') ?></strong>

                    <ul>
                        <li>
                            <?= Html::a(
                                Yii::t('action', 'Soon_run_out'),
                                ($sort === 'expiration_date' ? null : Url::current(['sort' => '-expiration_date'])),
                                [
                                    'class' => 'a-sorting-inner-group-types' . ($sort === 'expiration_date' ? ' active' : ''),
                                    'title' => Yii::t('action', 'Soon_run_out'),
                                ]
                            ) ?>
                        </li>
                        <li>
                            <?= Html::a(
                                Yii::t('business', 'view'),
                                ($sort === 'views' ? null : Url::current(['sort' => 'views'])),
                                [
                                    'class' => 'a-sorting-inner-group-types' . ($sort === 'views' ? ' active' : ''),
                                    'title' => Yii::t('business', 'Order by views'),
                                ]
                            ) ?>
                        </li>
                    </ul>
                </div>

                <div class="filter-sorting-inner-group filter-sorting-inner-group-order">
                    <ul>
                        <li>
                            <?php if ($sort && $order === SORT_ASC) : ?>
                                <?= Html::a('', null, ['class' => 'filter-sort-order-asc active']) ?>
                            <?php else : ?>
                                <?= Html::a('', Url::current(['sort' => $order_sort]), ['class' => 'filter-sort-order-asc']) ?>
                            <?php endif; ?>
                        </li>
                        <li>
                            <?php if ($sort && $order === SORT_DESC) : ?>
                                <?= Html::a('', null, ['class' => 'filter-sort-order-desc active']) ?>
                            <?php else : ?>
                                <?= Html::a('', Url::current(['sort' => "-$order_sort"]), ['class' => 'filter-sort-order-desc']) ?>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <?= Html::endForm() ?>

    </div>
</div>
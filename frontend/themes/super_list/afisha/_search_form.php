<?php
/**
 * @var $this \yii\web\View
 * @var $options array
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

        <?= Html::beginForm(['/search/index'], 'get', ['id' => 'find_afisha']) ?>

        <?= Html::hiddenInput('type', 1) ?>

        <div class="form-group form-group-keyword style-keyword-search">
            <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => Yii::t('business', 'Enter_search_phrase')]) ?>
        </div>

        <div class="form-group">
            <?= Html::input('submit', null, Yii::t('action', 'Search_btn'), ['style' => 'width: 266px']) ?>
        </div>

        <div class="filter-sorting-options clearfix">
            <div class="filter-sorting-inner">
                <div class="filter-sorting-inner-group filter-sorting-inner-group-types">
                    <ul>
                        <li>
                            <?= Html::a(Yii::t('afisha', 'Show_new_poster'),
                                ['/afisha/index', 'pid' => $options['pid'], 'genre' => $options['genre']],
                                ['class' => 'filter-sort-by-title afisha-li ' . ((($options['archive'] !== 'archive') and (Yii::$app->controller->action->id  === 'index')) ? 'active' : '')]
                            ) ?>
                        </li>
                        <li>
                            <?= Html::a(Yii::t('afisha', 'Now'),
                                ['/afisha/now', 'pid' => $options['pid'], 'genre' => $options['genre']],
                                ['class' => 'filter-sort-by-title afisha-li-short ' . ((Yii::$app->controller->action->id  === 'now') ? 'active' : '')]
                            ) ?>
                        </li>
                        <li>
                            <?= Html::a(Yii::t('afisha', 'For_the_week'),
                                ['/afisha/week', 'pid' => $options['pid'], 'genre' => $options['genre']],
                                ['class' => 'filter-sort-by-title afisha-li-short ' . ((Yii::$app->controller->action->id  === 'week') ? 'active' : '')]
                            ) ?>
                        </li>
                        <li>
                            <?= Html::a(Yii::t('afisha', 'Soon'),
                                ['/afisha/soon', 'pid' => $options['pid'], 'genre' => $options['genre']],
                                ['class' => 'filter-sort-by-title afisha-li-short ' . ((Yii::$app->controller->action->id  === 'soon') ? 'active' : '')]
                            ) ?>
                        </li>
                        <li>
                            <?= Html::a(Yii::t('afisha', 'Archive_poster'),
                                ['/afisha/index', 'archive' => 'archive', 'pid' => $options['pid'], 'genre' => $options['genre']],
                                [
                                    'class' => 'filter-sort-by-title afisha-li-short ' . ((($options['archive'] === 'archive') and (Yii::$app->controller->action->id  === 'index')) ? 'active' : ''),
                                    'style' => 'margin-top: 20px;',
                                ]
                            ) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <?= Html::endForm() ?>

    </div>
</div>
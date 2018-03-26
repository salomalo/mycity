<?php
/**
 * @var $url string
 * @var $s string
 * @var $pid string
 * @var $time DateTime
 * @var $activeLink string
 */


use common\models\Business;
use yii\helpers\Html;
use yii\helpers\Url;

$sort = Yii::$app->request->get('sort');
switch ($sort) {
    case Business::SORT_VIEWS_ASC:
        $sort_options = [
            'views' => ['get' => Business::SORT_VIEWS_DESC, 'class' => 'active'],
            'rating' => ['get' => Business::SORT_RATING_DESC, 'class' => null],
        ];
        break;
    case Business::SORT_VIEWS_DESC:
        $sort_options = [
            'views' => ['get' => Business::SORT_VIEWS_ASC, 'class' => 'active'],
            'rating' => ['get' => Business::SORT_RATING_DESC, 'class' => null],
        ];
        break;
    case Business::SORT_RATING_ASC:
        $sort_options = [
            'views' => ['get' => Business::SORT_VIEWS_DESC, 'class' => null],
            'rating' => ['get' => Business::SORT_RATING_DESC, 'class' => 'active'],
        ];
        break;
    case Business::SORT_RATING_DESC:
        $sort_options = [
            'views' => ['get' => Business::SORT_VIEWS_DESC, 'class' => null],
            'rating' => ['get' => Business::SORT_RATING_ASC, 'class' => 'active'],
        ];
        break;
    default:
        $sort_options = [
            'views' => ['get' => Business::SORT_VIEWS_DESC, 'class' => null],
            'rating' => ['get' => Business::SORT_RATING_DESC, 'class' => null],
        ];
}
?>
<div class="widget-inner widget-pb">
<!--    <form class="filter-form "></form>-->
    <?= Html::beginForm(Url::to(['/search/index']), 'get', ['id' => "find_business1"]) ?>
    <div class="form-group form-group-keyword style-keyword-search">
        <?= Html::input('text', 's', $s, ['placeholder' => Yii::t('business', 'Enter_search_phrase')]) ?>
        <?= Html::hiddenInput('type', $type) ?>
    </div>

    <div class="form-group">
        <?= Html::input('submit', null, Yii::t('action', 'Search_btn'), ['style' => 'width: 266px']) ?>
    </div>

    <?php if ($showVacantionLink) : ?>
        <div class="filter-sorting-options clearfix">
            <div class="filter-sorting-inner">
                <div class="filter-sorting-inner-group filter-sorting-inner-group-types">
                    <ul>
                        <li class="my-li-vacantion">
                            <?= Html::a(
                                Yii::t('vacantion', 'Job_employers'),
                                Url::to(['/vacantion/index']),
                                ['class' => Yii::$app->controller->id == 'vacantion' ? 'active' : '', 'title' => Yii::t('vacantion', 'Job_employers')]
                            ) ?>
                        </li>
                        <li class="my-li-vacantion">
                            <?= Html::a(
                                Yii::t('resume', 'Summary_of_applicants'),
                                Url::to(['/resume/index']),
                                ['class' => Yii::$app->controller->id == 'resume' ? 'active' : '', 'title' => Yii::t('vacantion', 'Job_employers')]
                            ) ?>
                        </li>
                    </ul>
                </div><!-- /.filter-sorting-inner-group -->
            </div><!-- /.filter-sorting-inner -->
        </div><!-- /.filter-sorting-options -->
    <?php endif;?>

    <?php if ($showActionLink) : ?>
        <div class="filter-sorting-options clearfix">
            <div class="filter-sorting-inner">
                <div class="filter-sorting-inner-group filter-sorting-inner-group-types">
                    <ul>
                        <li class="my-li-action">
                            <?= Html::a(
                                Yii::t('action', 'Show_new_shares'),
                                Url::to(['/action/index', 'pid' => $pid, 'time' => $time]),
                                ['class' => $activeLink == 'sotrNew' ? 'active' : '', 'title' => Yii::t('action', 'Show_new_shares')]
                            ) ?>
                        </li>
                        <li class="my-li-action">
                            <?= Html::a(
                                Yii::t('action', 'Soon_run_out'),
                                Url::to(['/action/index', 'pid' => $pid, 'time' => $time, 'sort' => 'time']),
                                ['class' => $activeLink == 'sotrTime' ? 'active' : '', 'title' => Yii::t('action', 'Soon_run_out')]
                            ) ?>
                        </li>
                        <li class="my-li-action">
                            <?= Html::a(
                                Yii::t('action', 'Archive_shares'),
                                Url::to(['/action/index', 'pid' => $pid, 'time' => $time, 'archive' => 'archive']),
                                ['class' => $activeLink == 'archive' ? 'active' : '', 'title' => Yii::t('action', 'Archive_shares')]
                            ) ?>
                        </li>
                    </ul>
                </div><!-- /.filter-sorting-inner-group -->
            </div><!-- /.filter-sorting-inner -->
        </div><!-- /.filter-sorting-options -->
    <?php endif;?>

    <?php if ($showAfishaLink) : ?>
    <div class="filter-sorting-options clearfix">
        <div class="filter-sorting-inner">
            <div class="filter-sorting-inner-group filter-sorting-inner-group-types">
                <ul>
                    <li>
                        <?= Html::a(Yii::t('afisha', 'Show_new_poster'),
                            ['/afisha/index', 'pid' => $pid, 'genre' => $genre],
                            ['class' => 'filter-sort-by-title afisha-li ' . ((($archive !== 'archive') and (Yii::$app->controller->action->id  === 'index')) ? 'active' : '')]
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('afisha', 'For_the_week'),
                            ['/afisha/week', 'pid' => $pid, 'genre' => $genre],
                            ['class' => 'filter-sort-by-title afisha-li-short ' . ((Yii::$app->controller->action->id  === 'week') ? 'active' : '')]
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('afisha', 'Soon'),
                            ['/afisha/soon', 'pid' => $pid, 'genre' => $genre],
                            ['class' => 'filter-sort-by-title afisha-li-short ' . ((Yii::$app->controller->action->id  === 'soon') ? 'active' : '')]
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('afisha', 'Archive_poster'),
                            ['/afisha/index', 'archive' => 'archive', 'pid' => $pid, 'genre' => $genre],
                            ['class' => 'filter-sort-by-title afisha-li-short ' . ((($archive === 'archive') and (Yii::$app->controller->action->id  === 'index')) ? 'active' : '')]
                        ) ?>
                    </li>
                </ul>
            </div><!-- /.filter-sorting-inner-group -->
        </div><!-- /.filter-sorting-inner -->
    </div><!-- /.filter-sorting-options -->
    <?php endif;?>

    <div class="filter-sorting-options clearfix">
        <div class="filter-sorting-inner">
            <div class="filter-sorting-inner-group filter-sorting-inner-group-types filter-sorting-types">
                <strong><?= Yii::t('business', 'Sort') ?></strong>


                <ul>
                    <li>
                        <?= Html::a(
                            Yii::t('business', 'view'),
                            Url::current(['sort' => $sort_options['views']['get']]),
                            ['class' => $sort_options['views']['class'] . ' a-sorting-inner-group-types', 'title' => Yii::t('business', 'Order by views')]
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            Yii::t('business', 'Rating'),
                            Url::current(['sort' => $sort_options['rating']['get']]),
                            ['class' => $sort_options['rating']['class'] . ' a-sorting-inner-group-types', 'title' => Yii::t('business', 'Rating')]
                        ) ?>
                    </li>
                    <li>
                        <a class="filter-sort-by-popularity a-sorting-inner-group-types">
                            Popularity <input type="hidden" name="sort-by"
                                              value="popularity" disabled="">
                        </a>
                    </li>
                </ul>
            </div><!-- /.filter-sorting-inner-group -->

            <div class="filter-sorting-inner-group filter-sorting-inner-group-order">
                <ul>
                    <li>
                        <a class="filter-sort-order-asc ">
                            <input type="hidden" name="order" value="asc" disabled="">
                            Asc </a>
                    </li>
                    <li>
                        <a class="filter-sort-order-desc active">
                            <input type="hidden" name="order" value="desc" disabled="">
                            Desc </a>
                    </li>
                </ul>
            </div><!-- /.filter-sorting-inner-group -->

        </div><!-- /.filter-sorting-inner -->
    </div><!-- /.filter-sorting-options -->
    <?= Html::endForm() ?>
</div>
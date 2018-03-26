<?php
/**
 * @var $this \yii\web\View
 * @var $business \common\models\Business
 * @var $model common\models\Comment
 * @var $countComments integer
 * @var $lvlGoodComment integer
 * @var $countCommentPrice integer
 * @var $countGoodCommentPrice integer
 * @var $countProdAvailability integer
 * @var $countGoodProdAvailability integer
 * @var $countCorrectDescription integer
 * @var $countGoodCorrectDescription integer
 * @var $countInTime integer
 * @var $countGoodInTime integer
 * @var $countCallback integer
 * @var $countGoodCallback integer
 */

$lvlGoodPrice = $countCommentPrice ? (integer)($countGoodCommentPrice / $countCommentPrice * 100) : 0;
$lvlGoodProdAvailability = $countProdAvailability ? (integer)($countGoodProdAvailability / $countProdAvailability * 100) : 0;
$lvlGoodDescription = $countCorrectDescription ? (integer)($countGoodCorrectDescription / $countCorrectDescription * 100) : 0;
$lvlGoodInTime = $countInTime ? (integer)($countGoodInTime / $countInTime * 100) : 0;
$lvlGoodCallback = $countCallback ? (integer)($countGoodCallback / $countCallback * 100) : 0;
?>

<table class="b-grids">
    <tbody>
    <tr>
        <td class="b-grids__item">
            <h2 class="h-mb-10" style="font-weight: 700;font-size: 18px;margin: 15px 0;">
                Оценки покупателей
                <span class="small-text grey">(12 месяцев)</span>
                <i class="b-icon-help js-popup-help" data-body="Критерии работы компании по оценкам покупателей, которые они оставили вместе с отзывами о заказе."></i>
            </h2>
            <table class="b-statistics-table">
                <tbody>
                <tr>
                    <th class="b-statistics-table__cell-title h-nowrap">Критерий</th>
                    <th class="b-statistics-table__cell-title h-nowrap">Средняя<br>оценка</th>
                    <th class="b-statistics-table__cell-title h-nowrap">Кол-во<br>оценок</th>
                </tr>
                <tr>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey" width="100%">
                        <span class="h-font-size-12">Актуальность цены</span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                                                <span class="b-progress b-progress_type_square b-progress_color_green">
                                                    <span class="b-progress__bg-grey"></span>
                                                    <span class="b-progress__bar" style="width: <?= $lvlGoodPrice ?>%"></span>
                                                    <span class="icon-progress_square b-progress__square"></span>
                                                </span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_align_center b-statistics-table__cell_color_grey">
                        <?= $countCommentPrice ?>
                    </td>
                </tr>
                <tr>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey" width="100%">
                        <span class="h-font-size-12">Наличие товара</span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                                                <span class="b-progress b-progress_type_square b-progress_color_green">
                                                    <span class="b-progress__bg-grey"></span>
                                                    <span class="b-progress__bar" style="width: <?= $lvlGoodProdAvailability ?>%"></span>
                                                    <span class="icon-progress_square b-progress__square"></span>
                                                </span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_align_center b-statistics-table__cell_color_grey">
                        <?= $countProdAvailability ?>
                    </td>
                </tr>
                <tr>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey" width="100%">
                        <span class="h-font-size-12">Описание товара</span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                                                <span class="b-progress b-progress_type_square b-progress_color_green">
                                                    <span class="b-progress__bg-grey"></span>
                                                    <span class="b-progress__bar" style="width: <?= $lvlGoodDescription ?>%"></span>
                                                    <span class="icon-progress_square b-progress__square"></span>
                                                </span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_align_center b-statistics-table__cell_color_grey">
                        <?= $countCorrectDescription ?>
                    </td>
                </tr>
                <tr>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                        <span class="h-font-size-12">Доставка в срок</span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                                                <span class="b-progress b-progress_type_square b-progress_color_green">
                                                    <span class="b-progress__bg-grey"></span>
                                                    <span class="b-progress__bar" style="width: <?= $lvlGoodInTime ?>%"></span>
                                                    <span class="icon-progress_square b-progress__square"></span>
                                                </span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_align_center b-statistics-table__cell_color_grey">
                        <?= $countInTime ?>
                    </td>
                </tr>
                <tr>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                        <span class="h-font-size-12">Скорость ответа компании</span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_color_grey">
                                                <span class="b-progress b-progress_type_square b-progress_color_green">
                                                    <span class="b-progress__bg-grey"></span>
                                                    <span class="b-progress__bar" style="width: <?= $lvlGoodCallback ?>%"></span>
                                                    <span class="icon-progress_square b-progress__square"></span>
                                                </span>
                    </td>
                    <td class="b-statistics-table__cell b-statistics-table__cell_align_center b-statistics-table__cell_color_grey">
                        <?= $countCallback ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

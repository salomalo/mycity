<?php

namespace frontend\extensions\SuperListLinkPager;

use Yii;
use yii\widgets\LinkPager;
use yii\helpers\Html;

/**
 * Description of MyLinkPager
 *
 * @author dima
 */
class SuperListLinkPager extends LinkPager
{
    /**
     * Renders the page buttons.
     * @return string the rendering result
     */

    public $nextPageLabel;
    public $prevPageLabel;
    public $firstPageLabel;
    public $lastPageLabel;

    public function init()
    {
        $this->nextPageLabel = Yii::t('app', 'To_There');
        $this->prevPageLabel = Yii::t('app', 'To_this');
        $this->firstPageLabel = Yii::t('app', 'First');
        $this->lastPageLabel = Yii::t('app', 'Last');

        parent::init();
    }

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => empty($class) ? $this->pageCssClass : $class];
        if ($active) {
            //Html::addCssClass($options, $this->activePageCssClass);
            return Html::tag('span', $label, ['class' => $this->activePageCssClass]);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('span', $label, ['class' => $this->linkOptions['class']]);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
    }

    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        return Html::tag('div', implode("\n", $buttons), $this->options);
    }
}

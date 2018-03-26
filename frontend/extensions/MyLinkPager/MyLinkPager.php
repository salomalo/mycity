<?php

namespace frontend\extensions\MyLinkPager;

use Yii;
use yii\widgets\LinkPager;
use yii\helpers\Html;

/**
 * Description of MyLinkPager
 *
 * @author dima
 */
class MyLinkPager extends LinkPager
{
    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    
    public $nextPageLabel;
    public $prevPageLabel;
    public $firstPageLabel;
    public $lastPageLabel;
    
    public $options = ['class' => 'paginationMy'];
    
    public function init()
    {
        $this->nextPageLabel = Yii::t('app', 'To_There');
        $this->prevPageLabel = Yii::t('app', 'To_this');
        $this->firstPageLabel = Yii::t('app', 'First');
        $this->lastPageLabel = Yii::t('app', 'Last');
        
        parent::init();
    }
            
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons1 = [];
        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons1[] = $this->renderPageButton('<img src="img/prev.png" alt="" />' . $this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
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
            $buttons1[] = $this->renderPageButton($this->nextPageLabel.'<img src="img/next.png" alt="" />', $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        if ($currentPage > 0) {
            if ($currentPage == 1)
                $this->view->registerLinkTag(['rel' =>'prev','href' => $this->pagination->createUrl(-1,null,true) ]);
            else{
                $get['page'] = $currentPage -1;
                $this->view->registerLinkTag(['rel' =>'prev','href' => $this->pagination->createUrl($currentPage -1,null,true) ]);
            }
        }
        if($currentPage < $pageCount - 1){
            $this->view->registerLinkTag(['rel' =>'next','href' => $this->pagination->createUrl($currentPage +1,null,true)]);
        }

        return Html::tag('ul', implode("\n", $buttons1), $this->options) . Html::tag('ul', implode("\n", $buttons), $this->options);
    }
}

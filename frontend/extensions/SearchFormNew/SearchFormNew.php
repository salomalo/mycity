<?php

namespace frontend\extensions\SearchFormNew;

use common\models\BusinessCategory;
use yii;
use yii\base\Widget;

class SearchFormNew extends Widget
{
    public $controller = null;
    public $action;
    public $search = null;
    public $pid = null;
    public $index = null;
    public $genre;
    public $id_category;
    public $archive;
    public $time;
    public $activeLink;
    public $type;

    public function init()
    {
        if (empty($this->controller)) {
            $this->controller = 'search';
        }
        Assets::register($this->getView());
    }

    public function run()
    {
        return $this->render('view', [
            'url' => '/' . $this->controller . '/' . $this->action,
            's' => $this->search,
            'pid' => $this->pid,
            'showAfishaLink' => $this->action == 'afisha',
            'showActionLink' => $this->action == 'action',
            'showVacantionLink' => $this->action == 'vacantion' ||  $this->action == 'resume',
            'index' => $this->index,
            'categoryList' => BusinessCategory::getCategoryList(),
            'id_category' => $this->id_category,
            'genre' => $this->genre,
            'archive' => $this->archive,
            'time' => $this->time,
            'activeLink' => $this->activeLink,
            'type' => $this->type,
        ]);
    }
}

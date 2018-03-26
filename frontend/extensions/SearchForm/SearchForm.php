<?php

namespace frontend\extensions\SearchForm;

use yii;
use yii\base\Widget;

class SearchForm extends Widget
{
    public $controller = null;
    public $action;
    public $search = null;
    public $pid = null;

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
            'url' => "/{$this->controller}/{$this->action}",
            's' => $this->search,
            'pid' => $this->pid
        ]);
    }
}

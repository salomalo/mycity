<?php

namespace frontend\components\traits;

use common\models\Business;
use Yii;

trait BusinessTrait
{
    /**@var Business */
    public $businessModel;

    protected function initTemplate()
    {
        if ($this->businessModel->isActive && in_array($this->businessModel->price_type, [Business::PRICE_TYPE_FULL, Business::PRICE_TYPE_FULL_YEAR]) && $this->businessModel->type == Business::TYPE_SHOP)
        {
            $template = isset($this->businessModel->template->alias) ? $this->businessModel->template->alias : 'shop';
            Yii::$app->controller->view->theme->pathMap = [
                '@app/views'    => '@app/themes/' . $template,
            ];
            Yii::setAlias('@layout','@app/themes/' . $template . '/layouts/main.php');
            if (isset($this->businessModel->template->alias) && $this->businessModel->template->alias != 'shop') {
                $this->layout = '@app/themes/layout';
            }
            //'@app/themes/shop'
        }
    }
}
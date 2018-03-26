<?php
namespace backend\models\search;

use backend\models\Admin;
use common\models\search\Ticket as TicketSearch;
use yii;

class Ticket extends TicketSearch
{
    protected function getModelQuery()
    {
        $admin = Yii::$app->user->identity;

        if ($admin->level === Admin::LEVEL_SUPER_ADMIN) {
            return parent::getModelQuery();
        } else {
            return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
        }
    }
}

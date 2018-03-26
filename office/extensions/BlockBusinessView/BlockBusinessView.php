<?php

/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.11.2016
 * Time: 13:10
 */

namespace office\extensions\BlockBusinessView;

use common\models\ViewCount;
use Yii;

class BlockBusinessView extends \yii\base\Widget
{
    public $business;

    public function run()
    {
        if (Yii::$app->user->identity->id) {
            if (isset($this->business) && $this->business) {
                $countViews = ViewCount::find()
                    ->where(['item_id' => $this->business->id])
                    ->andWhere(['category' => ViewCount::CAT_BUSINESS])
                    ->sum('value');
                $countViews = $countViews == null ?  0 : $countViews;
            } else {
                $countViews = ViewCount::find()
                    ->leftJoin(
                        'business',
                        'business."id" = view_count."item_id"'
                    )
                    ->where(['business."idUser"' => Yii::$app->user->identity->id])
                    ->sum('value');
            }
        }else{
            $countViews = 0;
        }

        return $this->render('index', [
            'countViews' => $countViews,
        ]);
    }
}
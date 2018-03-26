<?php
namespace frontend\extensions\VkGroup;

use common\models\WidgetCityPublic;
use yii;
use yii\base\Widget;

class VkGroup extends Widget
{
    public function init()
    {
        VkGroupAssets::register($this->view);
    }

    public function run()
    {
        if (!Yii::$app->request->city) {
            return null;
        }

        $public = WidgetCityPublic::find()->select('group_id')
            ->where(['city_id' => Yii::$app->request->city->id, 'network' => WidgetCityPublic::VK])
            ->asArray()->one();

        if (empty($public['group_id'])) {
            return null;
        }

        return $this->render('vk_small', ['public_id' => $public['group_id']]);
    }
}

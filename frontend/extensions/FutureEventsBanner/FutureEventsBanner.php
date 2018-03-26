<?php
namespace frontend\extensions\FutureEventsBanner;

use common\models\Advertisement;
use common\models\WidgetCityPublic;
use frontend\extensions\ShowAdvertisement\ShowAdvertisement;
use yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class FutureEventsBanner extends Widget
{
    public $vk = true;

    public function init()
    {
        if ($this->vk) {
            VkBannerAssets::register($this->view);
        } else {
            FutureEventsBannerAssets::register($this->view);
        }
    }

    public function run()
    {
        $return = null;
        if ($ads = ShowAdvertisement::widget(['position' => Advertisement::POS_SIDE_SQUARE])) {
            $return = "<div style='margin-bottom: 10px;'>$ads</div>";
        }
        if ($city = Yii::$app->request->city) {
            if (!$return) {
                $return = $this->renderVk();
            }
        }
        return $return;
    }

    private function renderVk()
    {
        $public_id = WidgetCityPublic::find()->select('group_id')
            ->where(['city_id' => Yii::$app->request->city->id, 'network' => WidgetCityPublic::VK])
            ->asArray()->one();
        $public_id = ArrayHelper::getValue($public_id, 'group_id', null);
        if (!is_null($public_id)) {
            return $this->render('vk', ['public_id' => $public_id]);
        }

        return null;
    }
}

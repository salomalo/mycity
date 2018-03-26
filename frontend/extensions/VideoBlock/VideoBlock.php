<?php
namespace frontend\extensions\VideoBlock;

use common\models\City;
use yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class VideoBlock extends Widget
{
    public function init()
    {
        VideoBlockAssets::register($this->view);
        
        parent::init();
    }

    public function run()
    {
        $createJson = function ($config) {
            $count = count($config);

            $json[] = '{';
            foreach ($config as $key => $item) {
                $json[] = $key;
                $json[] = ':';

                if (is_string($item)) {
                    $json[] = "'$item'";
                } elseif (is_bool($item)) {
                    $json[] = $item ? 'true' : 'false';
                } else {
                    $json[] = $item;
                }

                if (--$count !== 0) {
                    $json[] = ',';
                }
            }
            $json[] = '}';

            return implode($json);
        };

        $config = $createJson([
            'videoURL' => 'Kl3UR74jsk0',
            'containment' => '#video-block',
            'showControls' => false,
            'autoPlay' => true,
            'loop' => true,
            'mute' => true,
            'startAt' => 6,
            'opacity' => 1,
            'addRaster' => true,
            'quality' => 'default',
        ]);
        
        $categories = ['Акции', 'Афиша', 'Новости', 'Предприятия', 'Вакансии', 'Объявления'];
        $selected = 3;
        $cities = ArrayHelper::map(Yii::$app->params['cities'][City::ACTIVE], 'id', 'title');

        return $this->render('main', [
            'config' => $config,
            'categories' => $categories,
            'cities' => $cities,
            'selected' => $selected
        ]);
    }
}

<?php
namespace office\extensions\VideoLoginForm;

use dektrium\user\models\LoginForm;
use dektrium\user\Module;
use yii;
use yii\base\Widget;

class VideoLoginForm extends Widget
{
    /** @var LoginForm $model */
    public $model;
    
    /** @var Module $module */
    public $module;
    
    public function init()
    {
        VideoLoginFormAssets::register($this->view);
        
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

        return $this->render('main', ['config' => $config, 'model' => $this->model, 'module' => $this->module]);
    }
}

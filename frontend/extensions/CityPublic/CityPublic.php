<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.16
 * Time: 10:42
 */

namespace frontend\extensions\CityPublic;

use common\models\WidgetCityPublic;
use InvalidArgumentException;
use yii;
use \yii\base\Widget;
use \yii\web\View;

/**
 * Class CityPublic
 * @package frontend\extensions\CityPublic
 */
class CityPublic extends Widget
{
    public $city = 0;
    public $net = 'vk';
    private $vkJsUrl = 'http://vk.com/js/api/openapi.js?121';

    /**
     * @return string
     */
    public function run()
    {
        $widget = WidgetCityPublic::find()->where(['city_id' => $this->city, 'network' => $this->net])->one();
        if ($widget) {
            return $this->regJs()->render($this->net, ['widget' => $widget]);
        }
    }

    /**
     * @return $this
     */
    private function regJs()
    {
        switch ($this->net) {
            case 'vk':
                $this->view->registerJsFile($this->vkJsUrl, ['position' => View::POS_BEGIN]);
                break;
            case 'fb':
                $this->registerJsFile($this->net, View::POS_BEGIN);
                break;
            case 'tw':
                $this->registerJsFile($this->net, View::POS_READY);
                break;
            default:
                throw new InvalidArgumentException('Undefined social network key ' . $this->net);
        }
        return $this;
    }

    /**
     * @param $filename
     * @param $position
     */
    private function registerJsFile($filename, $position)
    {
        $path = __DIR__ . '/Assets/' . $filename . '.js';
        if ($js = file_get_contents($path)) {
            $this->view->registerJs($js, $position);
        }
    }
}

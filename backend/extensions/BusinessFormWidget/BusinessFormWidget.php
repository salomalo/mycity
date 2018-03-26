<?php
namespace backend\extensions\BusinessFormWidget;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

class BusinessFormWidget extends Widget
{
    public $model;
    public $_view;
    public $data;
    public $selectCity;
    public $relModelName='';
    public $readOnly = true;
    public $containerOptions = [];
    public $map = false;
    public $inForm = false;
    public $modelName = '';
    public $mapWidth = 'auto';
    public $getDataByAjax = true;
    public $essence;

    protected static $template = [];
    protected static $isInit = false;

    public function init()
    {
        $this->AssetBundle();
        parent::init();
    }

    public function run()
    {
        $init_address = 'Kiev';
        if ($city = Yii::$app->request->city) {
            $init_address = "{$city->title}, {$city->region->title}";
        }
        return $this->render('form', [
            'model' => $this->model,
            'endBody' => implode("\n", static::$template),
            'readOnly' => $this->readOnly,
            'init_address' => $init_address,
            'essence' => $this->essence,
        ]);
//        if (!$this->readOnly) {
//            echo '<div class="title-block-address">Адреса предприятия</div>';
//        }
//        echo Html::beginTag('div', ['id'=>'map-canvas', 'data-city' => $init_address]);
//        echo Html::endTag('div');
//
//        $this->renderEndBody();
    }

    public function AssetBundle()
    {
        $markerUrl = Assets::register($this->getView())->baseUrl . '/img/marker.png';
        $view = $this->getView();
        $css = " #map-canvas{width:".$this->mapWidth."; height:570px;margin-left: 18px;} .address_block {display:none;} ";
        $view->registerCss($css);
        $JavaScript[] = "var markerUrl = '{$markerUrl}'\n";

        if ($this->inForm) {
            $model = new $this->relModelName;
            $this->modelName = strtolower($model->formName());
            $JavaScript[] = "set_model_name('".$this->modelName."');";
        }
        $JavaScript[] = "google.maps.event.addDomListener(window, 'load', initialize);";
        if ($this->data || !$this->getDataByAjax) {
            $JavaScript[] = "set_data('" . Json::encode($this->data) . "');";
            if (is_array($this->data) and $this->data and isset($this->data[0]['lat']) and $this->data[0]['lat']) {
                $JavaScript[] = "set_data_center(" . $this->data[0]['lat'] . "," . $this->data[0]['lon'] . ");";
            } elseif ($this->selectCity) {
                $JavaScript[] = "set_select_city('" . $this->selectCity . "');";
            }
        } else {
            $JavaScript[] = "set_data_ajax();";
        }

        $view->registerJs(implode(" \n", $JavaScript), View::POS_END);

        if (!$this->readOnly) {
            $count = 1;

            //static::$template[] = $this->render('select-city');
            static::$template[] = '<div style="clear:both"></div>';
            static::$template[] = '<ul>';
            foreach($this->data as $item) {
                static::$template[] = $view->render($this->_view, [
                    'address'=>$item,
                    'count'=>$count,
                    'model'=> new $this->relModelName,
                    'readOnly'=>$this->readOnly
                ]);
                $count++;
            }
            static::$template[] = '<li class="address_add" id="main-address-add"><a href="#0" class="btn-success btn-xs" style="padding: 5px 10px;">Добавить адрес</a></li>';
            static::$template[] = '</ul>';
            static::$template[] = '<div style="clear:both"></div>';
        }
    }

    public static function renderEndBody()
    {
        echo implode("\n", static::$template);
    }
}
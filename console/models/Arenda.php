<?php
namespace console\models;

use common\models\Ads;
use common\models\City;
use common\models\Lang;
use common\models\ProductCategory;
use common\models\ProductCustomfield;
use InvalidArgumentException;

class Arenda
{
    const MARIUPOL_ID = 1277;
    const NIKOLAEV_ID = 1427;
    const ODESSA_ID = 1551;

    //General Ads fields
    public $title;
    public $price;
    public $city;

    //Custom fields
    public $building;
    public $flows;
    public $flow;
    public $rooms;
    public $area;
    public $area_in_use;
    public $type;
    public $ads_type;
    public $contact_name;
    public $contact_phone;

    //Images
    public $images;
    public $image;

    //Description
    public $phone;
    public $name;
    public $datestamp;
    public $description;

    //Unused
    public $url;

    //Special
    private $titleLen = 100;
    public $idCity;
    public static $cities = [
        'mariupol' => self::MARIUPOL_ID,
        'nikolaev_106' => self::NIKOLAEV_ID,
        'odessa' => self::ODESSA_ID,
    ];

    public static function insertCustomField($alias, $title, $type = true, $filter = false)
    {
        $custom = ProductCustomfield::find()->where(['alias' => $alias])->one();
        $custom = $custom ? $custom : new ProductCustomfield();
        $custom->alias = $alias;
        $custom->idCategory = self::getArenda()->id;
        $custom->type = $type ? ProductCustomfield::TYPE_STRING : ProductCustomfield::TYPE_DROP_DOWN;
        $custom->isFilter = $filter ? 1 : 0;
        $custom->title = $title;
        $custom->save();
    }

    public static function insertCustomFields()
    {
        $langs = ['ru', 'uk'];
        $f = [
                [
                    'alias' => 'building_type',
                    'title' => [
                        'ru' => 'Тип постройки',
                        'uk' => 'Тип споруди',
                    ],
                ],
                [
                    'alias' => 'building_flows',
                    'title' => [
                        'ru' => 'Этажность здания',
                        'uk' => 'Поверховість будівлі',
                    ],
                ],
                [
                    'alias' => 'apartment_flow',
                    'title' => [
                        'ru' => 'Этаж',
                        'uk' => 'Поверх',
                    ],
                ],
                [
                    'alias' => 'apartment_rooms',
                    'title' => [
                        'ru' => 'Количество комнат',
                        'uk' => 'Кількість кімнат',
                    ],
                ],
                [
                    'alias' => 'apartment_area',
                    'title' => [
                        'ru' => 'Общая площадь',
                        'uk' => 'Загальна площа',
                    ],
                ],
                [
                    'alias' => 'apartment_area_in_use',
                    'title' => [
                        'ru' => 'Жилая площадь',
                        'uk' => 'Житлова площа',
                    ],
                ],
                [
                    'alias' => 'rent_type',
                    'title' => [
                        'ru' => 'Тип аренды',
                        'uk' => 'Тип оренди',
                    ],
                ],
                [
                    'alias' => 'ads_type',
                    'title' => [
                        'ru' => 'Тип объявления',
                        'uk' => 'Тип оголошення',
                    ],
                ],
                [
                    'alias' => 'contact_name',
                    'title' => [
                        'ru' => 'Контактное лицо',
                        'uk' => 'Контактна особа',
                    ],
                ],
                [
                    'alias' => 'contact_phone',
                    'title' => [
                        'ru' => 'Контактный телефон',
                        'uk' => 'Контактний телефон',
                    ],
                ],
        ];
        foreach ($f as $i) {
            foreach ($langs as $lang) {
                Lang::setCurrent($lang);
                self::insertCustomField($i['alias'], $i['title'][$lang]);
            }
        }
    }

    public function setAdsFromArenda(Ads $ads)
    {
        Lang::setCurrent('ru');
        $ads->idCategory = self::getArenda()->id;
        $ads->images = (array)$this->images;
        $ads->title = $this->titleCrop((string)$this->title);
        $ads->price = (int)$this->price;
        $ads->description = (string)$this->description;
        if ($this->idCity) {
            $ads->idCity = (int)$this->idCity;
        } else {
            $ads->idCity = (int)$this->tryToSetCity();
        }
        $attr = [
            'building_type' => $this->building,
            'building_flows' => $this->flows,
            'apartment_flow' => $this->flow,
            'apartment_rooms' => $this->rooms,
            'apartment_area' => $this->area,
            'apartment_area_in_use' => $this->area_in_use,
            'rent_type' => $this->type,
            'ads_type' => $this->ads_type,
            'contact_name' => $this->name,
            'contact_phone' => $this->phone,
        ];

        $ads->initAttrForCategory($ads->idCategory, $attr);
        $ads->save();
    }

    public static function addCustomFieldCategory()
    {
        $langs = ['ru', 'uk'];
        $cats = [
            [
                'id' => isset(self::getRealty()->id) ? self::getRealty()->id : null,
                'pid' => false,
                'title' => [
                    'ru' => 'Недвижимость',
                    'uk' => 'Нерухомість',
                ],
            ],
            [
                'id' => isset(self::getArenda()->id) ? self::getArenda()->id : null,
                'pid' => true,
                'title' => [
                    'ru' => 'Аренда недвижимости',
                    'uk' => 'Оренда нерухомісті',
                ],
            ],
        ];
        foreach ($cats as $cat) {
            $obj = ProductCategory::findOne($cat['id']);
            if (!$obj) {
                $obj = new ProductCategory();
                foreach ($langs as $lang) {
                    Lang::setCurrent($lang);
                    $obj->title = $cat['title'][$lang];
                }
                if (!$cat['pid']) {
                    $obj->makeRoot();
                } else {
                    if (!($root = self::getRealty()->id)) {
                        echo 'не удалось найти ' . $obj->title . ' по url!';
                    }
                    $root = ProductCategory::findOne($root);
                    $obj->prependTo($root);
                }
            }
        }
    }

    private function tryToSetCity()
    {
        $pos = strpos($this->city, ',');
        $city = substr($this->city, 0, $pos);
        $id = City::find()->where(['~~*', 'title', ('%' . $city . '%')])->select('id')->one();
        return isset($id['id']) ? $id['id'] : self::$cities['mariupol'];
    }

    private function titleCrop($title)
    {
        if (!is_string($title)) {
            throw new InvalidArgumentException('titleCrop func only accepts string title. Input was: '
                . gettype($title));
        }
        if (strlen($title) > $this->titleLen) {
            $title = substr($title, 0, $this->titleLen);
            $lastTitleSpace = strrpos($title, ' ');
            $title = substr($title, 0, $lastTitleSpace);
        }
        return $title;
    }
    
    private static function getRealty()
    {
        return ProductCategory::findOne(['url' => 'neruhomist']);
    }

    private static function getArenda()
    {
        return ProductCategory::findOne(['url' => 'orenda-neruhomisti']);
    }

    public static function getArendaId()
    {
        $obj =self::getArenda();
        return isset($obj->id) ? $obj->id : null;
    }
}

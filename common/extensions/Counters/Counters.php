<?php

namespace common\extensions\Counters;

use common\models\City;
use common\models\Counter;
use common\models\CounterQuery;
use InvalidArgumentException;
use yii;
use yii\base\Widget;

class Counters extends Widget
{
    /** @var string $app */
    public $app = 'frontend';

    /** @var City $city */
    public $city;

    const FRONTEND = 'frontend';
    const OFFICE = 'office';

    public function init()
    {
        if (!is_string($this->app)) {
            $type = gettype($this->app);
            throw new InvalidArgumentException("app must be string {$type} given");
        }

        if (!$this->city) {
            $this->city = Yii::$app->request->city;
        }
    }

    public function run()
    {
        switch ($this->app) {
            case self::FRONTEND:
                return $this->getFrontendCounters();
            case self::OFFICE:
                return $this->getOfficeCounters();
            default:
                throw new InvalidArgumentException("Unknown app {$this->app}");
        }
    }

    /**
     * @return string
     */
    private function getFrontendCounters()
    {
        $query = $this->city ? Counter::find()->city($this->city->id)->orAllCities() : Counter::find()->main();
        $key = $this->city ? "counter_{$this->app}_{$this->city->id}" : "counter_{$this->app}_main";

        $counters = $this->getData($query, $key);

        return implode(PHP_EOL, $counters);
    }

    /**
     * @return string
     */
    private function getOfficeCounters()
    {
        $counters = $this->getData(Counter::find()->office(), "counter_{$this->app}");

        return implode(PHP_EOL, $counters);
    }

    /**
     * @param string $key
     * @return null|string[]
     */
    private function getFromCache($key)
    {
        if (Yii::$app->cache->exists($key)) {
            return Yii::$app->cache->get($key);
        }
        return null;
    }

    /**
     * @param string $key
     * @param string[] $value
     */
    private function setToCache($key, $value)
    {
        if (Yii::$app->cache->exists($key)) {
            Yii::$app->cache->delete($key);
        }
        Yii::$app->cache->set($key, $value);
    }

    /**
     * @param CounterQuery $query
     * @param string $key
     * @return string[]
     */
    private function getData($query, $key)
    {
        $counters = $this->getFromCache($key);
        if (is_null($counters)) {
            $counters = $query->getContents();
            $this->setToCache($key, $counters);
        }
        return $counters;
    }
}

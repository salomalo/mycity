<?php

namespace frontend\helpers;

use common\models\City;
use yii;
use yii\helpers\Url;
use yii\web\View;

/**
 * Description of SeoHelper
 *
 * @author dima
 */
class SeoHelper
{
    public static function addPageCount($page, $text)
    {
        return ($page > 1) ? $text . Yii::t('app', 'page') . $page : $text;
    }

    /**
     * @return bool|string
     */
    public static function redirectFromFirstPage()
    {
        $return = false;
        if (Yii::$app->request->get('page') === '1') {
            $get = Yii::$app->request->get();
            $get['page'] = null;
            $return = Url::to(array_merge(['index'], $get));
        }
        return $return;
    }

    /**
     * @param View $view
     * @param string $name
     * @param string $content
     * @param array $replace
     */
    public static function registerMetaTag(View &$view, $name = '', $content = '', $replace = [])
    {
        if (!empty($name)) {
            if (!empty($replace) and !empty($content)) {
                $content = str_replace(array_keys($replace), array_values($replace), $content);
            }
            $view->registerMetaTag(['name' => $name, 'content' => $content]);
        }
    }

    public static function registerAllMeta(View &$view, $meta = [])
    {
        $replace = self::getReplace();

        foreach ($meta as $name => $content) {
            $content = trim(strip_tags($content));
            self::registerMetaTag($view, $name, $content, $replace);
        }
    }

    /**
     * @param View $view
     * @param $title string
     * @return string
     */
    public static function registerTitle(View &$view, $title)
    {
        $replace = self::getReplace();

        $title = str_replace(array_keys($replace), array_values($replace), $title);
        $view->title = trim(strip_tags($title));
        
        return $view->title;
    }

    public static function getReplace()
    {
        $city = Yii::$app->request->city;
        $domain = Yii::$app->params['appFrontend'];
        if ($city) {
            $replace = ['{city}' => $city->title, '{city_ge}' => $city->title_ge, '{url}' => "{$city->subdomain}.$domain"];
        } else {
            $replace = [
                '{url}' => $domain,
                ' города {city}' => '', 'города {city}' => '',
                ' {city}' => '', '{city}' => '',
                ' {city_ge}' => '', '{city_ge}' => '',
            ];
        }

        return $replace;
    }

    public static function registerOgImage($url = null)
    {
        if (!$url) {
            $url = 'https://' . Yii::$app->params['appFrontend'] . Yii::$app->params['defaultOgImage'];
        }
        Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $url]);
    }
}

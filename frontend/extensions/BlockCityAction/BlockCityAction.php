<?php
namespace frontend\extensions\BlockCityAction;

use common\models\City;
use yii\base\Widget;
use yii;

class BlockCityAction extends Widget
{
    public $layout = 'index';

    public function run()
    {
        $models = Yii::$app->params['cities'][City::ACTIVE];
        if (Yii::$app->request->city and isset($models[Yii::$app->request->city->id])) {
            unset($models[Yii::$app->request->city->id]);
        }

        $arr['title'] = '';
        $arr['link'] = '';
        $arr['label'] = '';
        $arr['attr'] = 'title';
        $arr['main'] = Yii::t('app', 'Main page');
        $arr['main_default_url'] = true;
        switch (Yii::$app->controller->id) {
            case 'afisha':
                $arr['label'] = $arr['title'] = Yii::t('afisha', 'Poster');
                $arr['link'] = '/afisha';
                $arr['attr'] = 'title_ge';
                break;
            case 'action':
                $arr['label'] = $arr['title'] = Yii::t('action', 'Promotions');
                $arr['link'] = '/action';
                $arr['attr'] = 'title_ge';
                $arr['main'] = Yii::t('action', 'Promotions') . ' ' . Yii::t('app', 'of Main');
                $arr['main_default_url'] = false;
                break;
            case 'post':
                $arr['label'] = $arr['title'] = Yii::t('post', 'Posts');
                $arr['link'] = '/post';
                $arr['attr'] = 'title_ge';
                $arr['main'] = Yii::t('post', 'Posts') . ' ' . Yii::t('app', 'of Main');
                $arr['main_default_url'] = false;
                break;
            case 'business':
                $arr['attr'] = 'title_ge';
                switch (Yii::$app->controller->action->id) {
                    case 'map':
                        $arr['title'] = Yii::t('business', 'business_on_map');
                        $arr['link'] = '/map/business';
                        $arr['label'] = Yii::t('business', 'map');
                        break;
                    default:
                        $arr['label'] = $arr['title'] = Yii::t('business', 'Business');
                        $arr['link'] = '/business';
                }
                break;
            case 'search':
                $arr['attr'] = 'title_ge';
                switch (Yii::$app->controller->action->id) {
                    case 'map':
                        $arr['title'] = Yii::t('business', 'business_on_map');
                        $arr['link'] = '/map/business';
                        $arr['label'] = Yii::t('business', 'map');
                        break;
                    case 'business':
                        $arr['label'] = $arr['title'] = Yii::t('business', 'Business');
                        $arr['link'] = '/business';
                        break;
                    case 'action':
                        $arr['label'] = $arr['title'] = Yii::t('action', 'Promotions');
                        $arr['link'] = '/action';
                        break;
                }
                break;
            case 'vacantion':
                $arr['label'] = $arr['title'] = Yii::t('vacantion', 'Vacantions');
                $arr['link'] = '/vacantion';
                $arr['attr'] = 'title_ge';
                break;
            case 'resume':
                $arr['label'] = $arr['title'] = Yii::t('resume', 'Summary');
                $arr['link'] = '/resume';
                $arr['attr'] = 'title_ge';
                break;
            case 'ads':
                $arr['label'] = $arr['title'] = Yii::t('ads', 'Ads');
                $arr['link'] = '/ads';
                $arr['attr'] = 'title_ge';
                break;
            case 'site':
                $arr['title'] = ($this->layout === 'landing') ? Yii::t('app', 'Our_cities') : Yii::t('app', 'We_are_in_cities');
                break;
            case 'profile':
                $arr['title'] = ($this->layout === 'landing') ? Yii::t('app', 'Our_cities') : Yii::t('app', 'We_are_in_cities');
                break;
            case 'security':
                $arr['title'] = Yii::t('afisha', 'Poster');
                break;
            case 'registration':
                $arr['title'] = ($this->layout === 'landing') ? Yii::t('app', 'Our_cities') : Yii::t('app', 'We_are_in_cities');
                break;
        }

        return $this->render($this->layout, ['models' => $models, 'arr' => $arr]);
    }
}

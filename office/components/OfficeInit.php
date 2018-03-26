<?php
namespace office\components;

use yii;
use yii\base\ActionEvent;
use yii\base\Event;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;
use common\models\City;

class OfficeInit extends Request
{
    public function init()
    {
        //$this->checkCity();
        $this->setCities();
    }

    private function checkCity()
    {
        //Если пользователь авторизировался
        if (($user = Yii::$app->user->identity) && !Yii::$app->request->isAjax) {

            //При попытке открыть любую страницу
            Event::on(Controller::className(), Controller::EVENT_BEFORE_ACTION, function (ActionEvent $event) use ($user) {
                $redirect = 'security/city';
                $path = "{$event->action->controller->id}/{$event->action->id}";

                //Если он не модератор и у него не заполнен город, и запрос не к странице заполнения города
                if (!$user->checkCity() && ($path !== $redirect)) {
                    //Попросить заполнить город
                    Url::remember(Yii::$app->request->url, 'back_from_city');
                    Yii::$app->response->redirect([$redirect]);

                    //Иначе если город заполнен, а запрос к странице заполнения города
                } elseif (($path === $redirect) && $user->checkCity()) {
                    //Переадресовать на главную
                    Yii::$app->response->redirect(['/'], 301);
                }
            });
        }
    }

    public function setCities()
    {
        /** @var City[] $models */
        $models = City::find()->where(['not', ['main' => City::CLOSED]])->orderBy(['main' => SORT_DESC, 'id' => SORT_ASC])->all();
        $cities = [];

        foreach ($models as $city) {
            $cities[$city->main][$city->id] = $city;
        }

        Yii::$app->params['cities'] = $cities;
    }
}

<?php
namespace frontend\components;

use Yii;
use yii\web\UrlManager;
use common\models\Lang;

class LangUrlManager extends UrlManager
{
    public function createUrl($params, $scheme = false)
    {
        if (isset($params['lang_id'])) {
            //Если указан идентификатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Lang::findOne($params['lang_id']);
            if (!$lang) {
                $lang = Lang::getDefaultLang();
            }
            unset($params['lang_id']);
        } else {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Lang::getCurrent();
        }

        //Добавляем поддомен
        $serverName = '/';
        if (!empty($params['subdomain'])) {
            $serverName = Yii::$app->request->serverName;
            $serverName = "http://{$params['subdomain']}.{$serverName}/";
            unset($params['subdomain']);
        } elseif ($scheme) {
            $serverName = $this->hostInfo . '/';
        }

        //Получаем сформированный URL(без префикса идентификатора языка)
        $url = parent::createUrl($params);

        //Добавляем к URL префикс - буквенный идентификатор языка
        if ($url === '/') {
            return $serverName . $lang->url;
        } else {
            return $serverName . $lang->url . $url;
        }
    }
}
<?php

namespace frontend\extensions\TopBusiness;

use Yii;
use common\models\Business;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\HttpException;

/**
 * Description of TopBusiness
 *
 * @author dima
 */
class TopBusiness extends \yii\base\Widget
{
    public $limit = 12;
    public $title = '';
    public $idCity;
    public $titleCity = '';
    public $template = 'index';
    
    public function run()
    {
        $errors = false;

        /* @var $models ActiveQuery */
        $models = Business::find();
        if(!$this->idCity) $errors = true;
        else{
            $now = new \DateTime();
            $now->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));
            $now->setTimestamp(strtotime(date('Y-m-d')));
            $date2 = $now->modify('+1 day');

            $models = $models
                ->joinWith('actions')
                ->where('action."dateEnd" >= ' . "'" . $now->format('Y-m-d 00:00:00') . "'")
                ->andWhere('action."dateStart" <= ' . "'" . $date2->format('Y-m-d 00:00:00') . "'")
                ->andwhere(['idCity' => $this->idCity])
                ->andWhere('business.image <> :img', [':img' => ''])
                ->orderBy('action."dateEnd" ASC, ratio DESC')
                ->limit($this->limit)
                ->all();
            /* @var $models Business[]*/

            if($new_limit = ($this->limit - count($models))) {
                $old_models = ArrayHelper::getColumn($models, 'id');

                /* @var $new_models ActiveQuery*/
                $new_models = Business::find()
                    ->where(['idCity' => Yii::$app->params['SUBDOMAINID']])
                    ->andWhere(['not in', 'id', $old_models])
                    ->limit($new_limit)
                    ->orderBy('ratio DESC')
                    ->all();
                /* @var $new_models Business[]*/

                $models = ArrayHelper::merge($models, $new_models);
            }

            if(!$models) $errors = true;
        }
        
        return $errors ? false :
            $this->render($this->template, [
            'models' => $models,
            'title' => $this->title,
            'idCity' => $this->idCity,
            'titleCity' => $this->titleCity,
        ]);
    }
}
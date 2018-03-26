<?php

namespace frontend\extensions\EndUpActions;

use Yii;
use common\models\Action;
/**
 * Description of LastNews
 *
 * @author dima
 */
class EndUpActions extends \yii\base\Widget
{
    public $title = '';
    public $city = '';
    public $idCity;
    public $limit;
    
    public function run()
    {
        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));
        $now =  $datetime->format('Y-m-d H:i:s');
        
        $models = Action::find()->select('action.*')->with(['companyName', 'category']);
        
        if ($this->limit) {
            $models = $models->limit($this->limit);
        } else {
            $models = $models->where(['idCategory' => $this->idCategory])->limit(5);
        }
        
        $models = $models->andWhere('action.image != :img', [':img' => '']);
        
        $models = $models->andWhere('"dateEnd" >= :n', [':n' => $now]);
        
        $date2 = $datetime->modify('+1 day');
        $models = $models->andWhere('"dateStart" <= ' . "'" . $date2->format('Y-m-d 00:00:00') . "'");
        
        if ($this->idCity) {
            $models = $models->leftJoin('business', 'business.id = action."idCompany"');
            $models = $models->andWhere(['business."idCity"' => $this->idCity]);
        }
        
        $models = $models->orderBy('"dateEnd" ASC')->all();
        
        if (!$models) {
            return false;
        }
            
//        return '';
        return $this->render('index', [
            'models' => $models,
            'title' => $this->title,
            'city' => $this->city,
        ]);
    }
}



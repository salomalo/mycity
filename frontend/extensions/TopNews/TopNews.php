<?php

namespace frontend\extensions\TopNews;

use common\models\Post;
/**
 * Description of TopNews
 *
 * @author dima
 */
class TopNews extends \yii\base\Widget
{
    public $limit = 4;
    public $title = '';
    public $city = '';
    public $idCity;
    #one week in second
    const TIME_SPAN = 604800;
    
    public function run()
    {
//        $models = Post::find()->where('')->limit($this->limit)->all();
        $models = Post::find(); // posts обязательно добавленные в countView 
        
        if($this->idCity){
            $models = $models->where(['idCity' => $this->idCity]);
            $models = $models->orWhere(['allCity' => true]);
        }
        else{
            $models = $models->where(['allCity' => true]);
        }

        $now = date("Y-m-d H:i:s", time());
        $weekago = date("Y-m-d H:i:s", time()- static::TIME_SPAN);

        $models = $models->andWhere(['between', "dateCreate", $weekago, $now]);
        $models = $models->andWhere(['status' => Post::TYPE_PUBLISHED]);
        
        $models = $models->joinWith('countView')->orderBy('count DESC')->limit($this->limit)->all();
        
        if(!$models){
            return false;
        }
        
        return $this->render('index', [
            'models' => $models,
            'title' => $this->title,
            'city' => $this->city
        ]);
    }
}

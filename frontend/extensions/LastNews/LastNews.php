<?php

namespace frontend\extensions\LastNews;

use common\models\Post;
use common\models\PostCategory;
/**
 * Description of LastNews
 *
 * @author dima
 */
class LastNews extends \yii\base\Widget
{
    public $title = '';
    public $city = '';
    public $idCity;
    public $idCategory = null;
    
    public function run()
    {
        $models = Post::find();
        
        $alias = null;
        if(!$this->idCategory){
            $models = $models->limit(4);    
        }
        else{
            $models = $models->where(['idCategory' => $this->idCategory])->limit(5); 
            $category = PostCategory::find()->select(['url', 'title'])->where(['id' => $this->idCategory])->one();
            $alias = $category->url;
            $this->title = $category->title;
        }
        
        if($this->idCity){
            $models = $models->andWhere(['idCity' => $this->idCity]);
            $models = $models->orWhere(['allCity' => true]);
            if($this->idCategory){
                $this->title .= ' ' . $this->city;
            }
        }
        else{
            $models = $models->andWhere(['allCity' => true]);
        }
        
        $models = $models->orderBy('id DESC')->all(); 
        
        if(!$models){
            return false;
        }
            
        return $this->render((!$this->idCategory)? 'all_news' : 'category_news', [
            'models' => $models,
            'aliasCategory' => $alias,
            'title' => $this->title,
        ]);
    }
}

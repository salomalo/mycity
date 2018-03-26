<?php
namespace frontend\extensions\LatestEvents;
use common\models\search\Afisha as AfishaSearch;
use common\models\search\Post   as PostSearch;
use Yii;

use common\models\Post;
/**
 * Description of LastNews
 *
 * @author dima
 */
class LatestEvents extends \yii\base\Widget
{
    public $title = 'События онлайн';
    
    public function run()
    {      
        $content ='';
        $modelafisha =  AfishaSearch::find()->orderBy(['id'=>SORT_DESC])->one();
        if (!empty($modelafisha)){
            $content .= $this->render('item',[
                'title' => $modelafisha->title,
                'image'   => \Yii::$app->files->getUrl($modelafisha, 'image'),
                'link' => Yii::$app->urlManager->createUrl(['afisha/view', 'id' => $modelafisha->id]),
            ]);
        }
        $modelpost   = PostSearch::find()->orderBy(['id'=>SORT_DESC])->one();
        if (!empty($modelpost)){
            $content .= $this->render('item',[
                'title' => $modelpost->title,
                'image'   => \Yii::$app->files->getUrl($modelpost, 'image'),
                'link' => Yii::$app->urlManager->createUrl(['post/view', 'id' => $modelpost->id]),
            ]);
        }
        
        return $this->render('index',[
            'content' => $content,
            'title' => $this->title,
        ]);
        
    }
}

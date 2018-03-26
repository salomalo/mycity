<?php

namespace frontend\extensions\FotoInPost; 

use common\models\Post;
use common\models\File;
use common\models\Gallery;
use InvalidArgumentException;

/**
 * Description of FotoInPost
 *
 * @author dima
 */
class FotoInPost extends \yii\base\Widget
{
    public $titlePhoto = '';
    public $titleVideo = '';
    public $showPhoto = true;
    public $showVideo = true;
    public $limit = 10;
    public $idCity;
    public $subdomainCity;
    public $titleLen = 150;
    public $isEllipsis = true;

    public function run()
    {
        $modelsPhoto = null;
        if ($this->showPhoto) {
            $modelsPhoto = Post::find()
                ->joinWith('comment')
                ->select(['post.id', 'title', 'post.dateCreate', 'post.image', 'post.url', 'comment_count' => 'COUNT(comment.id)'])
                ->where('post.image <> :img', ['img' => '']);
            if ($this->idCity) {
                $modelsPhoto->andWhere(['post.idCity' => $this->idCity]);
            } else {
                $modelsPhoto->andWhere(['post.allCity' => true]);
            }

            $modelsPhoto->andWhere(['status' => Post::TYPE_PUBLISHED])
                ->groupBy('post.id')
                ->orderBy(['post.id' => SORT_DESC])
                ->limit($this->limit);
            $modelsPhoto = $modelsPhoto->all();
        }

        $modelsVideo = false;
        if ($this->showVideo) {
            $modelsVideo = Post::find()
                ->joinWith('comment')
                ->select(['post.id', 'post.title', 'post.dateCreate', 'post.video', 'post.url', 'comment_count' => 'COUNT(comment.id)'])
                ->where('post.video <> :img', ['img' => '']);
            if ($this->idCity) {
                $modelsVideo->andWhere(['post.idCity' => $this->idCity]);
            } else {
                $modelsVideo->andWhere(['post.allCity' => true]);
            }
            $modelsVideo->andWhere(['status' => Post::TYPE_PUBLISHED])
                ->groupBy('post.id')
                ->orderBy(['post.id' => SORT_DESC])
                ->limit($this->limit);
            $modelsVideo = $modelsVideo->all();

            $needs = $this->limit - count($modelsVideo);
            if ($needs > 0) {
                $modelsVideoFromOtherCities = Post::find()
                    ->joinWith('comment')
                    ->select(['post.id', 'post.title', 'post.dateCreate', 'post.video', 'post.url', 'comment_count' => 'COUNT(comment.id)'])
                    ->where('post.video <> :img', ['img' => ''])
                    ->andWhere(['post.status' => Post::TYPE_PUBLISHED])
                    ->groupBy('post.id')
                    ->orderBy(['post.id' => SORT_DESC])
                    ->limit($needs)
                    ->all();
                $modelsVideo = array_merge($modelsVideo, $modelsVideoFromOtherCities);
            }
        }

        $view = $this->getView();

        Assets::register($view);

        return $this->render('index', [
            'modelsPhoto' => ($modelsPhoto) ? $modelsPhoto : null,
            'modelsVideo' => ($modelsVideo) ? $modelsVideo : null,
            'titlePhoto' => $this->titlePhoto,
            'titleVideo' => $this->titleVideo,
            'subdomainCity' => $this->subdomainCity
        ]);
    }

    public function titleCrop($title, $isEllipsis = null)
    {
        if (!is_string($title)) {
            throw new InvalidArgumentException('titleCrop func only accepts string title. Input was: '
                . gettype($title));
        }
        if (!is_null($isEllipsis) and !is_bool($isEllipsis)) {
            throw new InvalidArgumentException('titleCrop func only accepts bool ellipsis. Input was: '
                . gettype($isEllipsis));
        }

        if (strlen($title) > $this->titleLen) {
            if (is_null($isEllipsis)) {
                $isEllipsis = $this->isEllipsis;
            }
            $title = substr($title, 0, $this->titleLen);
            $lastTitleSpace = strrpos($title, ' ');
            $title = substr($title, 0, $lastTitleSpace);
            if ($isEllipsis) {
                $title .= '...';
            }
        }
        return $title;
    }
}
<?php

namespace common\extensions\Gallery;

use common\extensions\Gallery\Assets;
use yii\base\Widget;
use common\models\Gallery as GalleryModel;
use common\models\File;

class Gallery extends Widget {

    public $model;
    public $idGallery = null;
    public $type;
    public $mongo = false;
    public $isMongo = false;
    public $template = null;

    public function init() {
        $this->AssetBundle();
        return parent::init();
    }

    public function AssetBundle() {
             
        $view = $this->getView();
        
        if(!$this->mongo){
            
            $idGallery = null;
             
            $post = \Yii::$app->request->post();
            
            $where = (!$this->isMongo)? ['pid' => $this->model->id] : ['title' => $this->model->_id];
            $gallery = GalleryModel::find()->where($where)->andWhere(['type' => $this->type]);
            
            if(!empty($post['idGallery'])){
                $gallery = $gallery->andWhere(['id' => $post['idGallery']]);
                $idGallery = $post['idGallery'];
            }
            
            if($this->idGallery){
                $gallery = $gallery->andWhere(['id' => $this->idGallery]);
                $idGallery = $this->idGallery;
            }
            
            $gallery = $gallery->orderBy('id ASC')->asArray()->one();
            
            
            if(!$gallery){
                return false;
            }
            
            $galList = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $gallery['id']])->all();

            if(!$galList){
                return false;
            }
            
            $galleryList = GalleryModel::find()->where($where)->andWhere(['type' => $this->type]);
            
            $galleryList = $galleryList->orderBy('id ASC')->asArray()->all();
            
            $options = [];
    
            foreach ($galleryList as $item){
                $options[] = '<option '. (($idGallery == $item['id'])? 'selected="selected"':'') .' value="'.$item['id'].'">' . $item['title'] .'</option>'; 
            }
            
            echo $view->render('@common/extensions/Gallery/views/view', [
                'model' => $this->model,
                'gallery' => $gallery,
                'galList' => $galList,
                'options' => $options,
                'gal' => new GalleryModel()
            ]);
        } else {
            $gallery = File::find()->where(['type' => $this->type, 'pidMongo' => (string)$this->model->_id])->all();

            if (!$gallery){
                $galleries = GalleryModel::find()->where(['pid' => (string)$this->model->_id])->all();
                $gallery =File::find()
                    ->leftJoin('gallery','gallery.id = file.pid')
                    ->where(['gallery.pid' => (string)$this->model->_id])
                    ->andWhere(['file.type' => $this->type])
                    ->all();
            }

            if($gallery || (isset($this->model->images) && $this->model->images)){
                echo $view->render('@common/extensions/Gallery/views/viewMongo', [
                    'gallery' => $gallery,
                    'model' => $this->model,
                ]);
            } else {
                return false;
            }

        }
      
        Assets::register($this->getView());
    }

}

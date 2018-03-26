<?php

namespace common\extensions\fileUploadWidget\galleryActions;

use common\models\Gallery;
use yii\helpers\Json;

class UploadGallery extends \yii\base\Action {

    public $view = 'index';
    public $id;
    public $essence;

    public function run() {
        $this->id = $_GET['id'];
        $this->essence = $_GET['essence'];

        $model = new Gallery;

        \Yii::$app->files->upload($model, 'attachments' , $this->essence.'/'.$this->id);


        $tmb_arr = explode('.', $model->attachments);
        $tmb = $tmb_arr[0] . '_100.' . $tmb_arr[1];
        $arr = [
            'files' => [
                0 => [
                    'name' => $model->attachments,
                    'size' => $_FILES['Gallery']['size']['attachments'],
                    'type' => $_FILES['Gallery']['type']['attachments'],
                    'url' => 'https://s3-eu-west-1.amazonaws.com/files1q/gallerys/' . $this->essence.'/'.$this->id. '/'. $model->attachments,
                    'thumbnailUrl' => 'https://s3-eu-west-1.amazonaws.com/files1q/gallerys/' . $this->essence.'/'.$this->id. '/' . $tmb,
                    'deleteUrl' => \Yii::$app->UrlManager->createUrl([
                        $this->essence . '/deleteGallery', 
                        'essence' => $this->essence,
                        'idGallery' => $this->id,
                        'file' => $model->attachments
                        ]),
                    'deleteType' => 'DELETE'
                ]
            ]
        ];

//       $model->pid = $this->id;
//       echo "<pre>";
//       print_r($model);
//       echo "</pre>";
//       die();
       $model->saveAttach($this->id);

        echo Json::encode($arr);
    }

}

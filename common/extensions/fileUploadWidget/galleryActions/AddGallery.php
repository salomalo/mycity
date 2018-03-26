<?php

namespace common\extensions\fileUploadWidget\galleryActions;

use common\models\Gallery;
use yii\helpers\Json;
use common\extensions\fileUploadWidget\FileUploadUI;
use common\models\File;

class AddGallery extends \yii\base\Action {

    public $view = 'index';
    public $title;
    public $idmodel;
    public $type;
    public $model;

    public function run() {
        $list = [];

        if (isset($_POST)) {

            $this->title = $_POST['Gallery']['title'];
            $this->idmodel = $_POST['Gallery']['idmodel'];
            $this->type = $_POST['Gallery']['type'];
            $this->model = $_POST['Gallery']['model'];

            $model = new Gallery;

            $model->title = $this->title;
            $model->pid = $this->idmodel;
            $model->type = $this->type;
            if ($model->save()) {

            }
        }
        return $this->controller->redirect([$this->model . '/update', 'id' => $this->idmodel]);
    }

}

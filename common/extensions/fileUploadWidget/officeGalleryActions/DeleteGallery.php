<?php

namespace common\extensions\fileUploadWidget\officeGalleryActions;

use common\models\Gallery;
use common\models\File;
use yii\helpers\Json;

class DeleteGallery extends \yii\base\Action {

    public $view = 'index';
    public $idGallery;
    public $essence;
    public $idmodel;

    public function run() {

        if (isset($_GET['action']) && $_GET['action'] == 'delGallery') {
            $this->idGallery = $_GET['idGallery'];
            $this->essence = $_GET['essence'];
            $this->idmodel = $_GET['idmodel'];

            $model = Gallery::findOne($this->idGallery);

            $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $model->id])->all();

            if (!empty($files)) {

                $listFiles = [];
                foreach ($files as $item) {

                    $listFiles[] = $item->name;

                    $item->delete();
                }

                \Yii::$app->files->deleteFilesGallery($model, 'attachments', $listFiles, null, $this->essence . '/' . $this->idGallery);
            }

            if ($model) {
                $model->delete();
            }

            return $this->controller->redirect([$this->essence . '/add-gallery', 'id' => $this->idmodel]);
        }

        if (isset($_GET['file'])) {

            $this->essence = $_GET['essence'];
            $this->idGallery = $_GET['idGallery'];
            $file = $_GET['file'];

            if (!empty($file)) {

                $model = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $this->idGallery, 'name' => $file])->one();
                $model->delete();

                $listFiles[] = $file;
                \Yii::$app->files->deleteFilesGallery(new Gallery(), 'attachments', $listFiles, null, $this->essence . '/' . $this->idGallery);
                return true;
            }
            return false;
        }

        if (isset($_GET['action']) && $_GET['action'] == 'list') {

            $this->idGallery = $_GET['idGallery'];
            $this->essence = $_GET['essence'];

            $listFiles = [];

            $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $this->idGallery])->all();

            if (!empty($files)) {

                foreach ($files as $item) {

                    $tmb_arr = explode('.', $item->name);
                    $tmb = $tmb_arr[0] . '_100.' . $tmb_arr[1];

                    $listFiles['files'][] = [
                        'name' => $item->name,
                        'size' => $item->size,
                        'type' => '',
                        'url' => 'https://s3-eu-west-1.amazonaws.com/files1q/gallerys/' . $this->essence . '/' . $this->idGallery . '/' . $item->name,
                        'thumbnailUrl' => 'https://s3-eu-west-1.amazonaws.com/files1q/gallerys/' . $this->essence . '/' . $this->idGallery . '/' . $tmb,
                        'deleteUrl' => \Yii::$app->UrlManager->createUrl([
                            $this->essence . '/deleteGallery',
                            'essence' => $this->essence,
                            'idGallery' => $this->idGallery,
                            'file' => $item->name,
                        ]),
                        'deleteType' => 'DELETE'
                    ];
                }
            }

            echo Json::encode($listFiles);
//              echo '{"files":[{"name":"enXJ6lx23E.jpg","size":154762,"type":"image\/jpeg","url":"http:\/\/file-upload\/server\/php\/files\/enXJ6lx23E.jpg","thumbnailUrl":"http:\/\/file-upload\/server\/php\/files\/thumbnail\/enXJ6lx23E.jpg","deleteUrl":"http:\/\/file-upload\/server\/php\/?file=enXJ6lx23E.jpg","deleteType":"DELETE"}]}';
        }
    }

}

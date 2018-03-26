<?php
namespace common\components\files;

class UploadedFile extends \yii\web\UploadedFile
{
    public $path;
    public $newFileName;

    public function saveAsMy($path, $newFileName)
    {
        $this->path = $path;
        $this->newFileName = $newFileName;
        if ($this->error == UPLOAD_ERR_OK) {
            if (!$this->beforeSave())
                return false;
            $result =  \Yii::$app->s3->upload($this->tempName, $path . '/' . $newFileName);
            $this->afterSave();
            return $result;
        }
        return false;
    }

    protected function beforeSave()
    {
        return true;
    }

    protected function afterSave()
    {

    }
}

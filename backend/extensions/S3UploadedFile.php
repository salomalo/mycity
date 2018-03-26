<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 6/28/14
 * Time: 7:41 PM
 */

namespace backend\extensions;


class S3UploadedFile extends \yii\web\UploadedFile
{
    /**
     * Saves the uploaded file to Amazon S3.
     * Note that this method uses php's move_uploaded_file() method. If the target file `$file`
     * already exists, it will be overwritten.
     * @param string $file the file path used to save the uploaded file
     * @return boolean true whether the file is saved successfully
     */
    public function saveAs($file)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            return \Yii::$app->s3->upload($this->tempName, $file);
        }
        return false;
    }

    /**
     * Saves the uploaded file to Amazon S3 with unique name.
     * Note that this method uses php's move_uploaded_file() method. If the target file `$file`
     * already exists, it will be overwritten.
     * @return boolean true whether the file is saved successfully
     */
    public function save()
    {
        if ($this->error == UPLOAD_ERR_OK) {
            $fileName = uniqid() . '.' .$this->extension;
            return \Yii::$app->s3->upload($this->tempName, $fileName);
        }
        return false;
    }
} 
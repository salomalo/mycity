<?php

namespace common\components\files;
use PHPImageWorkshop\ImageWorkshop;

/**
 * Class UploadedImage
 * @method static UploadedImage getInstance($name)
 */
class UploadedImage extends UploadedFile
{
    protected $sizes;
    protected $sizesHeight;
    
    public function afterSave()
    {
        if (!empty($this->sizes)) {
            foreach ($this->sizes as $key => $value) {
                $layer = ImageWorkshop::initFromPath($this->tempName);
                if (!empty($this->sizesHeight[$key])) {                                   // указан массив с шириной
                    if(($value / $this->sizesHeight[$key]) >= floatval(1.5)){           // вертикальная картинка
                        $layer->resizeInPixel(null, $this->sizesHeight[$key], true);
                    } else {
                        $layer->resizeInPixel($value, null, true);
                    }
                } else {
                    $layer->resizeInPixel($value, null, true);
                }
                $layer->save("/tmp",$this->newFileName, false);

                $newFileName = explode('.',  $this->newFileName);
                \Yii::$app->s3->upload("/tmp/{$this->newFileName}", $this->path . '/' . $newFileName[0] .'_' .$value .'.' . $this->extension);
                unlink("/tmp/{$this->newFileName}");
            }
        }
        return parent::afterSave();
    }

    public function setSizes($sizes, $sizesHeight = null)
    {
        $this->sizes = $sizes;
        $this->sizesHeight = $sizesHeight;
    }

    protected function generateSizeName()
    {

    }
} 
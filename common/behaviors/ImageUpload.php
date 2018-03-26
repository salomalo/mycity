<?php
namespace common\behaviors;

use common\models\Ads;
use common\models\Advert;
use yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use common\models\File;
use yii\db\BaseActiveRecord;

/**
 * Class ImageUpload
 * @package common\behaviors
 *
 * @property ActiveRecord|ActiveRecordMongo|Ads|Advert $owner
 */
class ImageUpload extends Behavior
{
    /** @var array $attribute */
    public $attribute;

    /** @var string $id */
    public $id = 'id';

    /** @var string $pid */
    public $pid = 'pid';

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'uploadFile',

            BaseActiveRecord::EVENT_AFTER_INSERT => 'savePidIntoFile',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'savePidIntoFile',

            BaseActiveRecord::EVENT_BEFORE_DELETE => 'delFile',
        ];
    }

    public function savePidIntoFile()
    {
        $update = [$this->pid => (string)$this->owner->{$this->id}];

        if (is_string($this->attribute)) {
            File::updateAll($update, ['name' => $this->owner->{$this->attribute}]);
        } elseif (is_array($this->attribute)) {
            foreach ($this->attribute as $attr) {
                File::updateAll($update, ['name' => $this->owner->{$attr}]);
            }
        }
    }

    public function delFile()
    {
        if (is_string($this->attribute)) {
            Yii::$app->files->deleteFile($this->owner, $this->attribute);
            $this->owner->{$this->attribute} = '';
        } elseif (is_array($this->attribute)) {
            foreach ($this->attribute as $attr) {
                Yii::$app->files->deleteFile($this->owner, $attr);
                $this->owner->{$attr} = '';
            }
        }
    }

    public function uploadFile()
    {
        if (is_string($this->attribute)) {
            Yii::$app->files->upload($this->owner, $this->attribute);
        } elseif (is_array($this->attribute)) {
            foreach ($this->attribute as $attr) {
                Yii::$app->files->upload($this->owner, $attr);
            }
        }
    }
}
<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gallery".
 *
 * @property integer $id
 * @property string $type
 * @property integer $pid
 * @property string $title
 * @property string $alias
 */
class Gallery extends ActiveRecord
{
    public $count;

    use GetCityTrait;
    public $attachments;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pid', 'title'], 'required','message' => 'Поле не может быть пустым'],
            [['type'], 'integer'],
            [['pid'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'pid' => 'Pid',
            'title' => 'Название',
        ];
    }
    
    public function saveAttach($id){
        $model = File::find()->where(['name' => $this->attachments])->one();
//        foreach ($model as $item){
            $model->pid = $id;
//            $item->save(false, 'pid');
            $model->update();
//        }
        
    }

    public function getFrontendUrl()
    {
        return '/gallery/' . $this->id;
    }

    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }
    
    public function getAlias()
    {
        return isset(File::$typeAliases[$this->type]) ? File::$typeAliases[$this->type] : null;
    }
}

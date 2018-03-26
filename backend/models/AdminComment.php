<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_comment".
 *
 * @property integer $id
 * @property string $text
 * @property integer $admin_id
 * @property string $date_create
 * @property integer $type
 * @property string $object_id
 *
 * @property Admin $admin
 */
class AdminComment extends \yii\db\ActiveRecord
{
    const TYPE_LEAD = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'admin_id'], 'required'],
            [['admin_id', 'type'], 'integer'],
            [['date_create'], 'safe'],
            [['object_id'], 'string', 'max' => 50],
            [['text'], 'string'],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::className(), 'targetAttribute' => ['admin_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'admin_id' => 'Admin ID',
            'date_create' => 'Date Create',
            'type' => 'Type',
            'object_id' => 'Object Id',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'admin_id']);
    }
}

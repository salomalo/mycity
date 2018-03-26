<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "parse_history".
 *
 * @property integer $id
 * @property string $element
 * @property integer $parser_id
 * @property string $date
 */
class ParseHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parser_id'], 'integer'],
            [['date'], 'safe'],
            [['element'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'element' => 'Element',
            'parser_id' => 'Parser ID',
            'date' => 'Date',
        ];
    }
}

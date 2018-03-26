<?php

namespace common\models;

use common\models\traits\GetUserTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ticket_history".
 *
 * @property integer $id
 * @property integer $idTicket
 * @property string $body
 * @property string $dateCreate
 */
class TicketHistory extends ActiveRecord
{
    use GetUserTrait;
    public static function tableName()
    {
        return 'ticket_history';
    }

    public function rules()
    {
        return [
            [['idTicket', 'body'], 'required','message' => 'Поле не может быть пустым'],
            [['idTicket'], 'integer'],
            [['body'], 'string'],
            [['dateCreate'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idTicket' => 'Id Ticket',
            'body' => 'Body',
            'dateCreate' => 'Date Create',
        ];
    }
}

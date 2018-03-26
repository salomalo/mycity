<?php

namespace common\models;

use Yii;
use yii\base\Model;

class ShoppingCart extends Model
{
    public $idUser;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser'], 'required','message' => 'Поле не может быть пустым'],
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idUser' => 'Id User',
       
        ];
    }
}

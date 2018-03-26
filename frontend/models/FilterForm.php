<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
/**
 * Description of FilterForm
 *
 * @author dima
 */
class FilterForm extends Model
{
    public $minPrice;
    public $maxPrice;
    public $field = [];
    public $companies = [];
    
    public function rules()
    {
        return [
            [['minPrice', 'maxPrice'], 'required'],
            [['minPrice', 'maxPrice'], 'integer'],
            [['field','companies'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'minPrice' => 'От',
            'maxPrice' =>  'До'
        ];
    }
}

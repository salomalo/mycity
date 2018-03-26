<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.12.2016
 * Time: 12:29
 */

namespace office\models;

use common\models\RegistrationForm as Form;

class RegistrationForm extends Form
{
    public $city_id;

    public function rules()
    {
        $rules = parent::rules();
        $rules['cityIdRequired'] = ['city_id', 'required'];
        return $rules;
    }
}
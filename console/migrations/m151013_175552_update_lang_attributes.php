<?php

use common\models\Lang;

class m151013_175552_update_lang_attributes extends yii\db\Migration
{
    public $pref = 'common\models\\';
    public $list = [
        'Action',
        'ActionCategory',
        'Afisha',
        'AfishaCategory',
        'Business',
        'BusinessAddress',
        'BusinessCategory',
        'City',
        'CustomfieldCategory',
        'KinoGenre',
        'PaymentType',
        'Post',
        'PostCategory',
        'ProductCategory',
        'ProductCustomfield',
        'Region',
        'WorkCategory'
    ];
    
    public function up($id = null)
    {
        $langs = Lang::find()->select(['local'])->all();
        
        foreach ($this->list as $key => $modelList){
            if(!$id || $key >= $id){    // все модели или с указанного индекса
                
                $this->log($modelList,'', true);
                $class = $this->pref . $modelList;
                $newModel = new $class();
                
                $select = [];
                foreach ($newModel->translateAttributes() as $attr){    // аттрибуты для перевода
                    $select[] = $attr;
                    $this->log(' - '.$attr,'', true);
                }
                
//                foreach ($class::find()->select($select)->batch(100) as $b){    // перебираем модели
                foreach ($class::find()->orderBy('id ASC')->batch(100) as $b){    // перебираем модели
                    foreach ($b as $model){
                        $change = false;
                        
                        foreach ($select as $attr){     // перебираем атрибуты для перевода
                            $this->log($model->id . ' - ' .$modelList . ' -- '. $attr, 'green');
                            $this->log(' - ' . $model->{$attr},'');
                            
                            if(json_decode($model->{$attr},true)){
                                $this->log(' - Json' ,'r', true);
                            }
                            else{   // не Json
                                $change = true;
                                $model->{$attr} = $this->getJson($model->{$attr}, $langs);
                            }
                        }
                        
                        if($change){
                            if($model->save(false, $select)){
                                $this->log('Save' ,'g', true);
                            }
                            else{
                                $this->log('Error Save' ,'r', true);
                            }
                        }
                        
                        $this->log(' -------------------------------------------------------------------------------- ','', true);
                    }
                } 
            }
        }
    }
    
    public function getJson($text, $langs){
        $arr = [];
        foreach ($langs as $lang){
            $arr[$lang->local] = $text;
        }
        $json = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $this->log(' - ' . $json ,'', true);
        return $json;
    }
            
    function log($string, $color = false, $br = false)
    {
        if(!$color){
            echo $string;
        }
        elseif($color == 'red' || $color == 'r'){
            echo "\033[31m" . $string . "\033[0m";
        }
        elseif($color == 'green' || $color == 'g'){
            echo "\033[32m" . $string . "\033[0m";
        }
        if($br){
            echo "\n";
        }
        
        
    }

    public function down()
    {
        echo "m151013_175552_update_lang_attributes cannot be reverted.\n";

        return false;
    }
}

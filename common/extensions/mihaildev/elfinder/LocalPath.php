<?php
/**
 * Date: 23.01.14
 * Time: 22:47
 */

namespace common\extensions\mihaildev\elfinder;

use Yii;

class LocalPath extends BasePath{
    public $path;

    public $name = 'Root';

    public $options = [];

    public $access = ['read' => '*', 'write' => '*'];

    public function getUrl(){
        return Yii::getAlias('@web/'.trim($this->path,'/'));
    }

    public function getRealPath(){
//        $path = Yii::getAlias('@webroot/'.trim($this->path,'/'));
//        if(!is_dir($path))
//            mkdir($path, 0777, true);

        //return $path;
        return 'https://s3-eu-west-1.amazonaws.com/';
    }

    public function getRoot(){
        $options['driver'] = $this->driver;
        $options['path'] = $this->getRealPath();
        $options['URL'] = $this->getUrl();
        $options['defaults'] = $this->getDefaults();
        $options['alias'] = $this->getAlias();
        $options['mimeDetect'] = 'internal';
        //$options['onlyMimes'] = ['image'];
        $options['imgLib'] = 'gd';
        $options['attributes'][] = [
            'pattern' => '#.*(\.tmb|\.quarantine)$#i',
            'read' => false,
            'write' => false,
            'hidden' => true,
            'locked' => true
        ];
//echo \yii\helpers\BaseVarDumper::dump(\yii\helpers\ArrayHelper::merge($options, $this->options), 10, true); die('this'); 
        return \yii\helpers\ArrayHelper::merge($options, $this->options);
    }
} 
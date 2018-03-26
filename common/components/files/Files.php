<?php

namespace common\components\files;

use PHPImageWorkshop\Core\Exception\ImageWorkshopLayerException;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use Aws\S3\S3Client;
use common\models\File;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use yii\mongodb\ActiveRecord as ActiveRecordMongo;

class FileConfig extends \stdClass
{
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';

    public $name;
    public $url;
    public $alias;
    public $type;
    public $type_file;
    public $sizes;
    public $sizesHeight;

    public function aliasToPath()
    {
        return str_replace('.', '/', $this->alias);
    }
}

class Files extends Component
{
    public $defaultAlias;
    public $defaultUrl;
    
    public $config = [];

    public function init()
    {
        foreach ($this->config as $key=>$value)
        {
            $alias  = (isset($value['alias'])) ? $value['alias'] : $this->defaultAlias;
            $this->config[$key]['absoluteUrl'] = (isset($value['url'])) ? $value['url']:
                    $this->createUrlFromAlias($alias, $this->defaultUrl);
        }
    }

    /**
     * Return File instance belong type
     * @param string $type
     * @param \yii\db\ActiveRecord $model
     * @param string $attribute
     * @param array $sizes
     * @param $sizesHeight
     * @return UploadedFile
     * @throws Exception
     */
    protected function getInstance($type, $model, $attribute, $sizes, $sizesHeight)
    {
        if ($type == FileConfig::TYPE_FILE) {
            return UploadedFile::getInstance($model, $attribute);
        } elseif ($type == FileConfig::TYPE_IMAGE) {
            $instance = UploadedImage::getInstance($model, $attribute);

            if ($instance) {
                $instance->setSizes($sizes, $sizesHeight);
            }

            if (($attribute != 'gallery') and ($attribute != 'images')) {
                return $instance;
            }

            if (!$instance) {
                $instance = UploadedImage::getInstances($model, $attribute);
                for ($i = 0; $i < count($instance); $i++) {
                    $instance[$i]->setSizes($sizes);
                }
            }

            return $instance;
        }
        throw new Exception(500, 'File type not found');
    }

    public function uploadFromUrl($model, $attribute, $img, $galleryAlias = '')
    {
        $config = $this->getConfig($model, $attribute);
        
        $file = new UploadedImage();
        
        $file->name = $img['file'];
        $file->tempName = $img['file'];
        $file->type = $img['mimeType'];
        $file->size = $img['filesize'];
        $file->error = 0;
        if ($galleryAlias == '') {
            //$file->setSizes([100]);
            $file->setSizes($config->sizes, $config->sizesHeight);
        } else {
            $config->alias = $config->aliasToPath().'/'.$galleryAlias;
            $file->setSizes($config->sizes, $config->sizesHeight);
        }
        return $this->uploadToS3($model, $attribute, $config, $file);
    }

    /**
     * Upload File to S3
     * @param $model \yii\db\ActiveRecord
     * @param string $attribute attribute name in AR with image
     * @param string $galleryAlias
     * @return UploadedFile
     * @throws Exception
     */
    public function upload($model, $attribute = '', $galleryAlias = '')
    {
        $className = StringHelper::basename(get_class($model));
        $config = $this->getConfig($model, $attribute);
        if ($galleryAlias != '') {
            $config->alias = $config->aliasToPath() . '/' . $galleryAlias;
        }
        $file = $this->getInstance($config->type, $model, $attribute, $config->sizes, $config->sizesHeight);
        if (!is_array($file)) {
            if ($file) {

                try {
                    $newFileName = $this->uploadToS3($model, $attribute, $config, $file);
                    $model->{$attribute} = $newFileName;
                } catch (ImageWorkshopLayerException  $e){

                }
            } else {
                //если модель Business, то не загружаем старую картинку в empty атрибут
                if (empty($model->{$attribute}) && $className != 'Business') {
                    if (is_subclass_of($model, ActiveRecordMongo::className())) {
                        $oldFile = File::findOne(['pidMongo' => (string)$model->_id, 'type' => $config->type_file]);
                    } else {
                        if ($model->id != null) {
                            $oldFile = File::findOne(['pid' => $model->id, 'type' => $config->type_file]);
                        }
                    }
                    $model->{$attribute} = !empty($oldFile) ? $oldFile->name : '';
                }
            }
        } else {
            $toBase = [];
            foreach ($file as $item) {
                try {
                    $fileUpload = $this->uploadToS3($model, $attribute, $config, $item);
                    if ($fileUpload) {
                        $toBase[] = $fileUpload;
                    }
                } catch (ImageWorkshopLayerException  $e) {

                }
            }
            if (count($toBase) > 0) {
                $model->{$attribute} = $toBase;
            } elseif ($className != 'Business') {//если модель Business, то не загружаем старую картинку в empty атрибут
                $oldFiles = File::find()->select(['name']);
                if (is_subclass_of($model, ActiveRecordMongo::className())) {
                    $oldFiles->where(['pidMongo' => (string)$model->_id, 'type' => $config->type_file]);
                } else {
                    $oldFiles->where(['pid' => $model->id, 'type' => $config->type_file]);
                }
                $oldFiles = $oldFiles->column();
                $model->{$attribute} = !empty($oldFiles) ? $oldFiles : [];
            }
        }
    }

    public function getUrl($model, $attribute, $size = NULL, $file = NULL, $galleryAlias=NULL)
    {
        $config = $this->getConfig($model, $attribute);
        
        if($galleryAlias){
            $config->url .= '/'.$galleryAlias;
        }

        if(!$file){
            $value = $model->{$attribute};
        }
        else {
            $value = $file;
        }
        
        
        if($size){
            if (in_array((int)$size, $config->sizes)) {
                $size = '_'.$size.'.';
                if (!$file) $fileName = explode('.',  $model->{$attribute} );
                else $fileName = explode('.',  $file );
                if (isset($fileName[0]) and isset($fileName[1]))
                    $value = $fileName[0] . $size . $fileName[1];
                else $value = '';
            }
        }

        if ($value == null || empty($value)){
            return  '/img/noImg.jpg';
        } else {
            return $config->url . '/' . $value;
        }
    }

    public function getUrlGallery($model, $attribute, $size = NULL, $file = NULL)
    {
        $config = $this->getConfig($model, $attribute);
        
        $config->alias = $config->aliasToPath();
        
        $config->url = $this->defaultUrl . $config->alias;
        
        if(!$file){
            $value = $model->{$attribute};
        }
        else {
            $value = $file;
        }
        
//        if($size != NULL){
//            
//            if(in_array((int)$size, $config->sizes)){
//                $size = '_'.$size.'.';
//            
//                if(!$file){
//                    $fileName = explode('.',  $model->{$attribute} ); 
//                }
//                else{
//                    $fileName = explode('.',  $file ); 
//                }
//               
//                $value = $fileName[0].$size.$fileName[1];
//            }
//        }
      
        return $config->url . '/' . $value;
    }

    public function getPath($model, $attribute)
    {
        $config = $this->getConfig($model, $attribute);
        $value = $model->{$attribute};
        return $config->aliasToPath() . '/' . $value;
    }

    public function getGallery($model, $attribute, $size = NULL)
    {
        if(is_array($model->{$attribute}) && !$model->isNewRecord){
            
            $config = $this->getConfig($model, $attribute);
            //return \yii\helpers\BaseVarDumper::dump($config, 10, true); 
            $arr = [];
            foreach ($model->{$attribute} as $item){
                
                if($item != ''){
                        if($size == NULL){
                        $arr[] = $this->defaultUrl.$config->aliasToPath() . '/' .$item;
                    }

                    if($size != NULL){

                        if(in_array((int)$size, $config->sizes)){
                            $size1 = '_'.$size.'.';

                            $fileName = explode('.',  $item ); 

                                $arr[] = $this->defaultUrl.$config->aliasToPath() . '/' .$fileName[0].$size1.$fileName[1];
                        }
                    }
                }
                
            }
            return $arr;
        }
        return '';
        
       
    }

    public function deleteFile($model, $attribute, $bucket = null, $alias = null)
    {
//        if (empty($model->oldAttributes[$attribute]) or $model->oldAttributes[$attribute] == ''){
//            return;
//        }
        //$model->{$attribute} = $model->oldAttributes[$attribute];
        
        $config = $this->getConfig($model, $attribute);
        if (!$bucket) {
            $bucket = Yii::$app->s3->bucket;
        }
        if (!$alias) {
            $alias = $config->aliasToPath();
        }

        $s3 = S3Client::factory(['key' => Yii::$app->s3->key, 'secret' => Yii::$app->s3->secret]);

        if (!is_array($model->{$attribute})) {
            if (!empty($model->{$attribute})) {
                $this->deleteFromS3($s3, $bucket, $alias, $config, $model->{$attribute});
            }
        } else {
            foreach ($model->{$attribute} as $atr) {
                if (!empty($atr)) {
                    $this->deleteFromS3($s3, $bucket, $alias, $config, $atr);
                }
            }
        }
    }

    public function deleteAllFile($model, $attribute, $modeType, $bucket = null, $alias = null)
    {
        if (empty($model->oldAttributes[$attribute]) or $model->oldAttributes[$attribute] == ''){
            return;
        }
        $model->{$attribute} = $model->oldAttributes[$attribute];

        $config = $this->getConfig($model, $attribute);
        if (!$bucket) {
            $bucket = \Yii::$app->s3->bucket;
        }
        if (!$alias) {
            $alias = $config->aliasToPath();
        }

        $s3 = S3Client::factory([
            'key'    => \Yii::$app->s3->key,
            'secret' => \Yii::$app->s3->secret,
        ]);

        if(!is_array($model->{$attribute})){
            if(!empty($model->{$attribute})){
                $this->deleteAllFromS3($s3, $bucket, $alias, $config, $model->{$attribute}, $model->id, $modeType);
            }
        }
        else {
            foreach ($model->{$attribute} as $atr){
                if(!empty($atr)){
                    $this->deleteAllFromS3($s3, $bucket, $alias, $config, $atr, $model->id, $modeType);
                }
            }
        }

    }

    public function deleteFilesGallery($model, $attribute, $listFiles, $bucket = null, $alias = null)
    {
        $config = $this->getConfig($model, $attribute);
        
        if (!$bucket){
            $bucket = \Yii::$app->s3->bucket;
        }
        
        if (!$alias){
            $alias = $config->aliasToPath();
        }
        else{
            $alias = $config->aliasToPath().'/'.$alias;
        }
         
        $s3 = S3Client::factory([
            'key'    => \Yii::$app->s3->key,
            'secret' => \Yii::$app->s3->secret,
        ]);
        
        if(is_array($listFiles)){
            foreach ($listFiles as $file){
                $this->deleteFromS3($s3, $bucket, $alias, $config, $file);
            }
        } 
    }

    /**
     * Генерация имени, создание записи в таблице File, вызов сохранения объекта файла
     * (в объекте вызывается загрузка на Амазон)
     * @param $model ActiveRecord
     * @param $attribute string
     * @param $config FileConfig
     * @param $file UploadedFile|UploadedImage
     *
     * @return bool
     */
    public function uploadToS3($model, $attribute, $config, $file)
    {
        $fileName = uniqid() . '.'. $file->extension;

        if (!empty($file->name)) {

            $record = new File();
            $record->name = $fileName;
            $record->size = $file->size;
            $record->type = $config->type_file.'';
            $record->dateCreate = date('Y-m-d H:i:s');
            $record->save();

            if(!$model->isNewRecord && isset($model->oldAttributes[$attribute])){
                if($model->oldAttributes[$attribute] != ''){
                    $this->deleteFile($model, $attribute);
                }
            }

            $file->saveAsMy($config->aliasToPath(), $fileName);
            $model->{$attribute} = $fileName;

            return $file->newFileName;
        }

       return false;
    }

    /**
     * @param $s3 S3Client
     * @param $bucket string
     * @param $alias string
     * @param $config FileConfig
     * @param $attribute string
     * @throws \Exception
     */
    public function deleteFromS3($s3, $bucket, $alias, $config, $attribute)
    {
        /* @var $fd File */
        if ($fd = File::findOne(['name' => $attribute])) {
            $fd->delete();
        }

        if (!Yii::$app->params['isDev']) {
            $fileToDel = $alias.'/'.$attribute;
            $s3->deleteObject(['Bucket' => $bucket, 'Key' => $fileToDel]);

            if (($config->type == FileConfig::TYPE_IMAGE) and is_array($config->sizes)) {
                $fileName = explode('.', $attribute);
                foreach ($config->sizes as $item) {
                    $s3 = S3Client::factory(['key' => Yii::$app->s3->key, 'secret' => Yii::$app->s3->secret]);
                    $fileToDel = $alias.'/'.$fileName[0].'_'.$item.'.'.$fileName[1];
                    $s3->deleteObject(['Bucket' => $bucket, 'Key' => $fileToDel]);
                }
            }
        }
    }

    /**
     * @param $s3 S3Client
     * @param $bucket string
     * @param $alias string
     * @param $config FileConfig
     * @param $attribute string
     * @param $modelId integer
     * @param $modeType integer
     *
     * @throws \Exception
     */
    public function deleteAllFromS3($s3, $bucket, $alias, $config, $attribute, $modelId, $modeType)
    {
        $files = File::find()->where(['pid' => $modelId, 'type' => $modeType])->all();
        foreach ($files as $file){
            $file->delete();
        }


        if (!\Yii::$app->params['isDev']){
            $fileToDel = $alias.'/'.$attribute;
            $s3->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $fileToDel
            ]);

            if (($config->type == FileConfig::TYPE_IMAGE) and is_array($config->sizes)) {
                $fileName = explode('.',  $attribute );
                foreach ($config->sizes as $item) {
                    $s3 = S3Client::factory([
                        'key'    => \Yii::$app->s3->key,
                        'secret' => \Yii::$app->s3->secret,
                    ]);
                    $fileToDel = $alias.'/'.$fileName[0].'_'.$item.'.'.$fileName[1];
                    $s3->deleteObject([
                        'Bucket' => $bucket,
                        'Key'    => $fileToDel
                    ]);
                }
            }
        }
    }

//    /**
//     * Assign file by filename to entity
//     * @param array|string $filenames
//     * @param string $type
//     * @param int $pid
//     */
//    public function assign($filenames, $type, $pid)
//    {
//        if (is_string($filenames))
//        {
//            $filenames = array($filenames);
//        }
//        foreach($filenames as $filename)
//        {
//            $file = pathinfo($filename);
//            Yii::app()->ds->file->updateAll(array('type'=>$type,'pid'=>$pid),'pid IS NULL AND id = :name',array(
//                ':name'=>$file['filename']
//            ));
//        }
//    }
//
//    public function reAssign($filenames, $type, $pid)
//    {
//        Yii::app()->ds->file->updateAll(array('type'=>null,'pid'=>null),'type = :type AND pid = :pid',
//            array(':type'=>$type,':pid'=>$pid)
//        );
//        $this->assign($filenames, $type, $pid);
//    }

    protected function getConfig($model, $attribute)
    {
        $config = new FileConfig();
        $name = $this->getShortClassName($model);
        if ($attribute)
        {
            $name = $name.'_'.$attribute;
        }
        $name = strtolower($name);
        $config->name = $name;
        $config->alias  = (isset($this->config[$name]['alias']))        ? $this->config[$name]['alias']         : $this->defaultAlias;
        if ((isset($this->config[$name]['absoluteUrl'])))
        {
            $config->url = $this->config[$name]['absoluteUrl'];
        }
        elseif($config->alias)
        {
            $alias = str_replace($this->defaultAlias,'',$config->alias);
            $alias = trim($alias,'.');
            $config->url = $this->defaultUrl . '/' . str_replace('.','/',$alias);
        }
        else
        {
            $config->url = $this->defaultUrl;
        }
        $config->type   = (isset($this->config[$name]['type']))         ? $this->config[$name]['type']          : FileConfig::TYPE_FILE;
        $config->type_file   = (isset($this->config[$name]['type_file']))      ? $this->config[$name]['type_file']            : FileConfig::TYPE_FILE;
        $config->sizes  = (isset($this->config[$name]['sizes']))        ? $this->config[$name]['sizes']         : array();
        $config->sizesHeight  = (isset($this->config[$name]['sizesHeight']))        ? $this->config[$name]['sizesHeight']         : array();
        //echo \yii\helpers\BaseVarDumper::dump($config->type_file, 10, true); die('file');
        return $config;
    }

    protected function createUrlFromAlias($alias, $configUrl)
    {
        $posOfFirstPoint = strpos($alias, '.');
        $url = substr($alias, $posOfFirstPoint);
        $url = str_replace('.', '/', $url);
        return $configUrl.$url;    
    }

    protected function getShortClassName($object)
    {
        $class = explode('\\', (is_string($object) ? $object : get_class($object)));
        return $class[count($class) - 1];
    }
}

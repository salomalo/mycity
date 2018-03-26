<?php
namespace console\controllers;

use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use common\models\Business;
use common\models\BusinessCategory;
use common\models\BusinessAddress;
use common\models\BusinessTime;
use common\models\LogParseBusiness as LogParse;
use common\models\Gallery;
use common\models\File;
use common\models\ParserDomain;
use console\extensions\simpleParser\simple_html_dom;

/**
 * Description of ParserFirmaController
 *
 * @author dima
 */

define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT',	3);
define('HDOM_TYPE_ENDTAG',  4);
define('HDOM_TYPE_ROOT',	5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO',	 3);
define('HDOM_INFO_BEGIN',   0);
define('HDOM_INFO_END',	 1);
define('HDOM_INFO_QUOTE',   2);
define('HDOM_INFO_SPACE',   3);
define('HDOM_INFO_TEXT',	4);
define('HDOM_INFO_INNER',   5);
define('HDOM_INFO_OUTER',   6);
define('HDOM_INFO_ENDSPACE',7);
define('DEFAULT_TARGET_CHARSET', 'UTF-8');
define('DEFAULT_BR_TEXT', "\r\n");
define('DEFAULT_SPAN_TEXT', " ");
define('MAX_FILE_SIZE', 600000);

class ParserFirmaController extends Controller
{
    public $mDomain;
    public $domain;
    public $startCat = false;
    public $startPodCat = false;
    public $startPage = false;
    public $cat = '';
    public $podCat = '';
    public $lastFullLink = false;
    
    public $title;
    public $url;
    public $companyName;
    public $idMenu;
    
    function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
    {
            // We DO force the tags to be terminated.
            $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
            // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
            $contents = file_get_contents($url, $use_include_path, $context, $offset);
            // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
            //$contents = retrieve_url_contents($url);
            if (empty($contents) || strlen($contents) > MAX_FILE_SIZE) {
                return false;
            }
            // The second parameter can force the selectors to all be lowercase.
            $dom->load($contents, $lowercase, $stripRN);
            return $dom;
    }
    
    public function getDomains($idCity){
            $model = ParserDomain::find()->where(['idCity' => $idCity])->one();
            if($model){
                $this->domain = 'http://' . $model->domain;
                $this->mDomain = 'http://' . $model->mDomain;
            }
        }

    public function actionIndex($idCity, $parse_cat = '', $parse_Podcat = '', $page = '', $lastFullLink = ''){
        
        $this->getDomains($idCity);
        
        $site = @file_get_contents($this->mDomain . '/catalog');
        
        if ($site) {
            preg_match_all('~<div class="poster">(.+?)<footer>~is', $site, $match1);
            preg_match_all('~<div class="(.+?)">(.+?)<a href="(.+?)" class="news int">(.+?)<h2>(.+?)</h2>(.+?)</a>(.+?)</div>~is', $match1[1][0], $cat);
            
            foreach ($cat[3] as $key=>$linkMain){
                
                if($parse_cat == $cat[5][$key]){
                    $this->startCat = true;             
                }
                
                $this->log($key . ' - '. $cat[5][$key] . ' - ' . $linkMain . "\n") ;
                $this->cat = $cat[5][$key];
                
                if ($this->startCat || $parse_cat == '') {
                    
                    $site = @file_get_contents($this->mDomain . $linkMain);
                    preg_match_all('~<div class="poster">(.+?)<footer>~is', $site, $match1);
                    preg_match_all('~<div class="(.+?)">(.+?)<a href="(.+?)" class="news int">(.+?)<h2>(.+?)</h2>(.+?)</a>(.+?)</div>~is', $match1[1][0], $podCat);

                    foreach ($podCat[3] as $key=>$linkPodCat){
                        
                        if($parse_Podcat == $podCat[5][$key]){
                            $this->startPodCat = true;             
                        }
                        
                        $this->log("\t" . $key . ' - '. $podCat[5][$key] . ' - ' . $linkPodCat . "\n") ;
                        $this->podCat = $podCat[5][$key];
                        
                        if(($this->startPodCat || $parse_Podcat == '') && $podCat[5][$key] != 'Все подрубрики'){
                            
                            if(!empty($page)){
                                $this->getContent($idCity, $page, $lastFullLink);
                                $page = '';
                            }
                            else{
                                $content = $this->getContent($idCity, $linkPodCat, $lastFullLink);
                            }
                            
                        }

                    }
                }
                
            }
            
        } else {
            $this->log("no site \n");
        }
    }
    
    private function getContent($idCity, $url, $lastFullLink, $page = null)
    {
        if (!$page) {
            //$site = @file_get_contents($this->mDomain . $url);
            $html = $this->file_get_html($this->mDomain . $url);
            //preg_match_all('~<div class="enterprise">(.+?)<footer>~is', $site, $match);
            //preg_match_all('~<a href="(.+?)" class="block_item( last)?">(.+?)</a>~is', $match[1][0], $list); // список cсылок
            //preg_match_all('~<a href="(.+?)" class="news">~is', $match[1][0], $page); // список cсылок
            //preg_match_all('~<div class="block_item lastt">(.+?)</div>~is', $match[1][0], $page); 
            
//            foreach ($html->find('div.enterprise a.block_item') as $item){
//                
//            }
            
            $titles = [];
            //print_r($list[1]); die();
            foreach ($html->find('div.enterprise a.block_item') as $item){
                //echo($item->href);die('aaa');
                if($this->lastFullLink || empty($lastFullLink)){
                    $title = $item->find('h2.grn', 0);
                    $this->title = $this->cleanValue($title->plaintext);
                    $this->url = $item->href;
                    
                    $model = Business::find('id')->where(['title' => $this->title, 'idCity' => $idCity])->one();
                    if(!$model){
                        $this->getFirma($idCity, $item->href, $url);
                    } 
                }
                
                if($item->href == $lastFullLink){
                    $this->lastFullLink = true;
                }
                
            }
            
            $nextPage = $html->find('div.enterprise div.lastt a.news', 0);  // след. страница
            if(!empty($nextPage->href)){
                $this->getContent($idCity, $nextPage->href, $lastFullLink);
            }
            
            $html->clear(); 
            unset($html);
        }
        else{
            
        }
    }
    
    public function getFirma($idCity, $fullLink, $url){
        
        $temp = explode('/', $fullLink);
        
        if(empty($temp[3])){
            return;
        }
        
        $html = $this->file_get_html($this->domain . $fullLink);
        
        if(!$html){
            unset($html);
            return;
        }
        
        $business = new Business();
        $business->title = $this->title;
        $business->idCity = $idCity;
        $business->idUser = 1;
        $business->idProductCategories = [];
        
        $this->log("\n\t\t" . $this->title . ' - ' . $fullLink . "\n");
        
        $coord = ['lon' => 0, 'lat' => 0];
        $time = '';
        
        foreach ($html->find('div.block_info div') as $infoBox){
            
            $name = $infoBox->find('span.name', 0);
                if(!empty($name)){
                    $name->plaintext = trim($name->plaintext);
                    $this->log("\t\t\t" . $name->plaintext . " - ");

                    foreach ($infoBox->find('span.text') as $value){

                        if($name->plaintext == 'Адрес'){
                            $coord = $this->getCoord($infoBox);
                            $address = $this->cleanValue($value->plaintext);
                        }

                        if($name->plaintext == 'Телефон'){
                           $business->phone = $this->cleanValue($value->plaintext);
                        }

                        if($name->plaintext == 'Время работы'){
                           $time = $value->plaintext;
                        }

                        if($name->plaintext == 'Сайт'){
                           $business->site = $value->plaintext;
                        }

                        if($name->plaintext == 'Twitter'){
                           $business->urlTwitter = $value->plaintext;
                        }

                        if($name->plaintext == 'Vk'){
                           $business->urlVK = $value->plaintext;
                        }

                        if($name->plaintext == 'Fb'){
                           $business->urlFB = $value->plaintext;
                        }

                        if($name->plaintext != 'Email адрес'){
                            $value->plaintext = trim($value->plaintext);
                            $this->log($this->cleanValue($value->plaintext) . "\n");
                        }
                        else{
                            $business->email = $this->getEmail($value);
                            $this->log($business->email . "\n");
                        }
                    }
                }
        }
        
        if($coord['lon'] != 0){
            $this->log("\t\t\t" . 'lon' . " - " . $coord['lon'] . "\n");
            $this->log("\t\t\t" . 'lat' . " - " . $coord['lat'] . "\n");
        }
        
        $desc = $html->find('div.company_info div.text_info', 0);
        if(!empty($desc->plaintext)){
            $this->log("\t\t\t" . 'Desc - ' . $desc->plaintext . "\n");
            $business->description = $desc->plaintext;
        }
       
        $arrCat = [$this->saveCategory($this->cat, $this->podCat)];
        
        foreach ($html->find('div.company_info h2.open span') as $catList){
            $this->log('cat - ' . $catList->plaintext . "\n");
            
            $temp = explode(' / ', $this->cleanValue($catList->plaintext));
            if(!empty($temp[1]) && is_array($temp)){
                $arrCat[] = $this->saveCategory($temp[0], $temp[1]);
            }
        }
        $business->idCategories = array_unique($arrCat);
        
        echo("\n");
        
        if ($business->save()) {
            $this->lastFirma($idCity, $url, $fullLink);
            
            if ($time != '') {
//                $res = $this->getTime($business->id, preg_replace('/ {2,}/',' ',$time));
//
//                if(!$res[0]){
//                    //$this->log("\t\t\t" . "\033[31m" . 'E-mail - ' . $res[1] . "\033[0m \n"); die();
//                }
            }
            
            if ($coord['lat'] != 0) {
                $modelAddress = new BusinessAddress();
                $modelAddress->idBusiness = $business->id;
                $modelAddress->lat = $coord['lat'];
                $modelAddress->lon = $coord['lon'];
                $modelAddress->address = $address;
                $modelAddress->phone = $business->phone;
                $modelAddress->save();
            }
            
            $logo = $html->find('div.comp_logo img', 0);
            if ($logo) {
                $this->log("\t\t\t" . 'Лого - ' . $logo->src . "\n");
                $img = $this->img($logo->src);
                if($img['mimeType'] != 'text/html'){
                    $file = \Yii::$app->files->uploadFromUrl($business, 'image', $img);
                    if(!empty($file)){
                        $business->image = $file;
                        $business->save(false, ['image']);
                    }
                }
                unlink($img['file']);
            }
            
            $gal = $html->find('div.gallery_box', 0);
            
            if($gal){

                $albom = $gal->find('option[selected=selected]', 0);

                $this->log("\t\t\t" . 'Галерея - ' . $albom->plaintext . "\n");
                
                $modelGallery = new Gallery();
                $modelGallery->type = File::TYPE_BUSINESS;
                $modelGallery->pid = $business->id;
                $modelGallery->title = $this->cleanValue($albom->plaintext);
                $modelGallery->save();

                foreach ($gal->find('div.foto_content ul li a img') as $img) {
                    $img = $this->img($img->src);
                    if($img['mimeType'] != 'text/html'){
                        $file = \Yii::$app->files->uploadFromUrl($modelGallery, 'attachments', $img, 'business/'.$modelGallery->id);
                        if(!empty($file)){
                            $modelFile = File::find()->select('id')->where(['type'=>File::TYPE_GALLERY, 'name'=>$file])->one();
                            $modelFile->pid = $modelGallery->id;
                            $modelFile->save(false, ['pid']);
                        }
                    }
                    unlink($img['file']);
                }
            }
        }
        
        unset($business);
       
        $html->clear(); 
        unset($html);
    }
    
    private function getTime($idBusiness, $value)
    {
        preg_match_all('/^с (.*?) до (.*?) ежедневно( ?)(\.?)$/iu', $value, $match);  // с 9:00 до 18:00  ежедневно
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, [$match[1][0], $match[2][0]]);
        }
        
        preg_match_all('/^Пн-Пт: (.*?) Сб: (.*?) Вс: (.*?)$/iu', $value, $match);  // Пн-Пт: 9:00-18:00 Сб: 10:00-16:00 Вс: 10:00-16:00
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, $match[1][0], false, true, $match[2][0], true, $match[3][0]);
        }
        
        preg_match_all('/^с (.*?) до (.*?)\, Воскресенье - выходной(.*?)(\.?)$/iu', $value, $match);  // с 9-00 до 18-00, Воскресенье - выходной.
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, [$match[1][0], $match[2][0]], false, true, '', false, '');
        }
        
        preg_match_all('/^Пн-Пт: с (.*?) до (.*?) Сб-Вс: выходной( ?)(\.?)$/iu', $value, $match);  // Пн-Пт: с 8:00 до 17:00 Сб-Вс: выходной.
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, [$match[1][0], $match[2][0]], false, false, '', false, '');
        }
        
        preg_match_all('/^с (.*?) до (.*?)\, сб\. с (.*?) до (.*?)\, вс\. выходной( ?)(\.?)$/iu', $value, $match);  // с 08-00 до 17-00, сб. с 08-00 до 16-00, вс. выходной
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, [$match[1][0],$match[2][0]], false, true, [$match[3][0], $match[4][0]], false, '');
        }
        
        preg_match_all('/^пн\.-пт\. с (.*?) до (.*?) \,сб\. с (.*?) до (.*?)\, вс\. выходной( ?)(\.?)$/iu', $value, $match);  // пн.-пт. с 9-00 до 18-00 ,сб. с 9-00 до 16-00, вс. выходной
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, [$match[1][0],$match[2][0]], false, true, [$match[3][0], $match[4][0]], false, '');
        }
        
        preg_match_all('/^Пн-Пт с (.*?) Сб-Вс с (.*?)$/iu', $value, $match);  // Пн-Пт с 8.00-18.00  Сб-Вс с 8.00-17.00
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, $match[1][0], false, true, $match[2][0], true, $match[2][0]);
        }
        
        preg_match_all('/^Понедельник-Четверг (.*?); Пятница (.*?); Выходной - Суббота\, Воскресенье( ?)(\.?)$/iu', $value, $match);  // Понедельник-Четверг 9:00 - 16:30;  Пятница  9:00 - 15:30;  Выходной - Суббота, Воскресенье
        if(!empty($match[1][0])){
            return $this->setTime2($idBusiness, $match[1][0], true, $match[2][0], false, '', false, '');
        }
        
        preg_match_all('/^(.*?)\, сб (.*?)\, вс (.*?)(\.?)$/iu', $value, $match);  // 8:00 - 18:00, сб 9:00 - 17:00, вс 9:00 - 15:00 Возможно посещение по предварительной записи
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, $match[1][0], false, true, $match[2][0], true, $match[3][0]);
        }
        
        preg_match_all('/^ПН-СБ: с (.*?) до (.*?)\, ВС: с (.*?) до (.*?)(\.?)$/iu', $value, $match);  // ПН-СБ: с 09:00 до 21:00, ВС: с 10:00 до 18:00
        if(!empty($match[1][0])){
            $t1 = $match[1][0].'-'.$match[2][0];
            $t2 = $match[3][0].'-'.$match[4][0];
            return $this->setTime($idBusiness, $t1, false, true, $t1, true, $t2);
        }  
        
        preg_match_all('/^с (.*?) до (.*?)$/iu', $value, $match);  // с 9:00 до 18:00
        if(!empty($match[1][0])){
            return $this->setTime($idBusiness, [$match[1][0], $match[2][0]]);
        }  
        
        return [0 => false, 1 => $value];
    }
    
    private function setTime2($idBusiness, $time, $fr=false, $frTime='', $sat=false, $satTime='', $sun=false, $sunTime='')
    {
        $r = explode('-', $time);
        for($i=1; $i<=4; $i++){
            $model = new BusinessTime();
            $model->idBusiness = $idBusiness;
            $model->weekDay = $i;
            $model->start = $this->checkTime($r[0]);
            $model->end = $this->checkTime($r[1]);
            $model->save();
        }
        if($fr && $frTime!=''){
            $r = explode('-', $frTime);
            $model = new BusinessTime();
            $model->idBusiness = $idBusiness;
            $model->weekDay = 5;
            $model->start = $this->checkTime($r[0]);
            $model->end = $this->checkTime($r[1]);
            $model->save();
        }
        
        return [0 => true];
    }
    
    private function setTime($idBusiness, $time, $all=true, $sat=false, $satTime='', $sun=false, $sunTime='')
    {
        if($all){
            for($i=1; $i<=7; $i++){
                $model = new BusinessTime();
                $model->idBusiness = $idBusiness;
                $model->weekDay = $i;
                $model->start = $this->checkTime($time[0]);
                $model->end = $this->checkTime($time[1]);
                $model->save();
            }
        }
        
        if(!$all && $sat && $satTime!='' && $sun && $sunTime!=''){
            $r = explode('-', $time);
            for($i=1; $i<=5; $i++){
                $model = new BusinessTime();
                $model->idBusiness = $idBusiness;
                $model->weekDay = $i;
                $model->start = $this->checkTime($r[0]);
                $model->end = $this->checkTime($r[1]);
                $model->save();
            }
            
            $sa = explode('-', $satTime);
            
            $model = new BusinessTime();
            $model->idBusiness = $idBusiness;
            $model->weekDay = 6;
            $model->start = $this->checkTime($sa[0]);
            $model->end = $this->checkTime($sa[1]);
            $model->save();
            
            $su = explode('-', $sunTime);
            
            $model = new BusinessTime();
            $model->idBusiness = $idBusiness;
            $model->weekDay = 7;
            $model->start = $this->checkTime($su[0]);
            $model->end = $this->checkTime($su[1]);
            $model->save();
            
        }
        
        if(!$all && $sat && $satTime=='' && !$sun){
            for($i=1; $i<=6; $i++){
                $model = new BusinessTime();
                $model->idBusiness = $idBusiness;
                $model->weekDay = $i;
                $model->start = $this->checkTime($time[0]);
                $model->end = $this->checkTime($time[1]);
                $model->save();
            }
        }
        
        if(!$all && $sat && $satTime!='' && !$sun){
            for($i=1; $i<=5; $i++){
                $model = new BusinessTime();
                $model->idBusiness = $idBusiness;
                $model->weekDay = $i;
                $model->start = $this->checkTime($time[0]);
                $model->end = $this->checkTime($time[1]);
                $model->save();
            }
            
            $model = new BusinessTime();
            $model->idBusiness = $idBusiness;
            $model->weekDay = 6;
            $model->start = $this->checkTime($satTime[0]);
            $model->end = $this->checkTime($satTime[1]);
            $model->save();
        }
        
        if(!$all && !$sat && !$sun){
            for($i=1; $i<=5; $i++){
                $model = new BusinessTime();
                $model->idBusiness = $idBusiness;
                $model->weekDay = $i;
                $model->start = $this->checkTime($time[0]);
                $model->end = $this->checkTime($time[1]);
                $model->save();
            }
        }
        return [0 => true];
    }
    
    private function checkTime($time)
    {   
        $string = trim(str_replace(['.', ' - ', '-'], ':', $time));
        $string = preg_replace("/[^0-9:\s]/", "", $string);
        $string = str_replace(" ", "", $string);
        if(strlen($string) > 5){
            $string = substr($string, 0, 5);
        }
        
        if(strlen($string) < 3){
            $string = $string . ':00';
        }
        return $string;
    }
    
    private function saveCategory($cat, $podCat)
    {
        $model = BusinessCategory::find()->where(['title' => $cat])->one();
        if (!$model) {
            $model = new BusinessCategory();
            $model->title = $cat;
            $model->image = '';
            $model->pid = '';
            $model->save();
        }
        
        $modelPodCat = BusinessCategory::find()->where(['pid' => $model->id ,'title' => $podCat])->one();
        if(!$modelPodCat){
            $modelPodCat = new BusinessCategory();
            $modelPodCat->pid = $model->id;
            $modelPodCat->title = $podCat;
            $modelPodCat->image = '';
            $modelPodCat->save();
        }
        
        return $modelPodCat->id;
    }
    
    public function img($url){
        if (!empty($url))
        {
            $file = basename($url);
            if (file_get_contents($url))
            {
                $content = file_get_contents($url);
                $f = fopen( "$file", "w" );
                if (fwrite( $f, $content ) === FALSE)
                {
                    echo "Не могу произвести запись в файл.";
                    return;
                }
                else {
                    $filesize = filesize($file); 
                    $mimeType = mime_content_type($file);
                    echo "\033[32m" . "Файл " .$file ." записан. ".$filesize . " байт. mime: ". $mimeType . "\033[0m" . "\n";
                    fclose( $f );
                    return ['file' => $file, 'filesize' => $filesize, 'mimeType' => $mimeType];
                }
            }
            else echo "\033[31m" . "Не могу качать файл. \033[0m". "\n";
        }
    }
    
    public function getEmail($value){
        preg_match_all('/<span class="text">(.*?)mail_user=\'(.*?)\'(.*?)\+\'(.*?)\'(.*?)<\/span>/iu', $value, $match);
        if(!empty($match[2][0])){
            $email =  $match[2][0].'@'.$match[4][0];
        }
        else{
            preg_match_all('/<span class="text">(.*?)mail_user = \'(.*?)\'(.*?)\+ \'(.*?)\'(.*?)<\/span>/iu', $value, $match);
            if(!empty($match[2][0])){
                $email =  $match[2][0].'@'.$match[4][0];
            }
            else{
                $email = '';
            }
        }
        return $email;
    }
    
    public function getCoord($infoBox){
        foreach ($infoBox->find('span.address') as $map){
            preg_match_all('/<span class="address" data-markerinfo=\'(.*?)\'>(.*?)<\/span>/iu', $map, $match);
            if(!empty($match[1][0])){
                $list = explode(',', str_replace('"', '', $match[1][0]));
                $coord = [];
                foreach ($list as $item){
                    $arr = explode(':', $item);
                    
                    if($arr[0] == 'geo_coord_lng'){
                        $coord['lon'] = $arr[1];
                    }
                    
                    if($arr[0] == 'geo_coord_lat'){
                        $coord['lat'] = $arr[1];
                    }
                }
                
                return $coord;
            }
        }
        $coord['lon'] = 0;
        $coord['lat'] = 0;
        return $coord;
    }
    
    public function lastFirma($idCity, $url, $fullLink){
        $file = "lastFirma.log";
        
        $str = $idCity. ";". $this->cat . ";" . $this->podCat. ";" . $url . ";" . $fullLink . ";";
        
        $f = fopen( "$file", "w" );
        if(fwrite( $f, $str ) === FALSE){
            echo "\033[31m" . "Не могу сохранить файл. \033[0m". "\n";
        }
        else{
            //echo "\033[32m" . "Файл " .$file ." записан. \033[0m" . "\n";
            $this->log('Добавленно - ' . $str . "\n", false, true);
        }
    }
    
    public function cleanValue($value)
    {
        $value = strip_tags($value);
        $value = trim($value);
        $arr = ["\r\n", "\\"];
        $value = str_replace($arr, ' ', $value);
        return $value;
    }
    
    function log($string, $fail = false, $db = false)
    {
        if($fail){
            echo "\033[31m" . $string . "\033[0m";
        }
        else{
            echo $string ;
        }
        
        \Yii::info($string, 'parse');
        if ($db) {
            $log = new LogParse();
            $log->title = $this->title;
            $log->url = $this->url;
            $log->message = $string;
            $log->isFail = $fail ? 1 : 0;
            $log->save();
        }
    }
}

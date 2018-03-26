<?php

namespace frontend\extensions\Rating;

use common\models\Rating as RatingModel;
/**
 * Description of Rating
 *
 * @author dima
 */
class Rating extends \yii\base\Widget
{
    public $type;
    public $id;
    public $idUser;
    private $model;


    public function run()
    {
        if(empty($this->type) || empty($this->id)){
            return false;
        }
        
        $this->idUser = (!empty(\Yii::$app->user->identity->id))? \Yii::$app->user->identity->id : 0;
        
        $this->model = RatingModel::find()->select(['rating', 'id'])->where(['type' => $this->type, 'pid' => $this->id])->one();
        
        $view = $this->getView();

        Assets::register($view);
        
        $js = '$(document).on("click", ".rating-up", function(e){
    
            e.preventDefault();
            var rating = $(this).parent().find("span.rating-val");
            
            send("up", rating);
        });

        $(document).on("click", ".rating-down", function(e){

            e.preventDefault();
            var rating = $(this).parent().find("span.rating-val");

            send("down", rating);
        });
        
    function getXmlHttp() {
        var xmlhttp;
        try {
          xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
        try {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
          xmlhttp = false;
        }
        }
        if (!xmlhttp && typeof XMLHttpRequest!="undefined") {
          xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
    }
  
    function send(doRating, rating) {
        var xmlhttp = getXmlHttp(); // Создаём объект XMLHTTP
        xmlhttp.open(\'POST\', \'/index.php?r=site/get-ajax\', true); // Открываем асинхронное соединение
        xmlhttp.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\'); // Отправляем кодировку
        xmlhttp.send("site=" + encodeURIComponent("http://api.mycity/index.php?r=rating%2Fupdate&id=" + '.$this->id.') + encodeURIComponent("&type=" + '.$this->type.') + encodeURIComponent("&do=" + doRating) + encodeURIComponent("&idUser=" + '.$this->idUser.')); // Отправляем POST-запрос
        xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
            if (xmlhttp.readyState == 4) { // Ответ пришёл
                if(xmlhttp.status == 200) { // Сервер вернул код 200 (что хорошо)
                    console.log(xmlhttp.responseText);
                    rating.text(xmlhttp.responseText);
                  //document.getElementById("yandex").innerHTML = xmlhttp.responseText; // Выводим ответ сервера
                }
            }
        };
    }
        ';
        
        $view->registerJs($js);

        return $this->render('index', [
            'rating' => ($this->model)? $this->model->rating : 0,
            'idUser' => $this->idUser
        ]);
    }
}

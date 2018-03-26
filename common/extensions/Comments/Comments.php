<?php

namespace common\extensions\Comments;

use InvalidArgumentException;
use Yii;
use yii\base\Widget;
use common\models\Comment;
/**
 * Description of Comments
 *
 * @author dima
 *
 * @property string $js
 * @property string $comment
 * @property string $action
 */
class Comments extends Widget {

    public $id;
    public $mongo = false;
    public $type;
    public $limit = 3;
    public $action;

    public function run()
    {
        if (!is_string($this->action) and !is_null($this->action)) {
            throw new InvalidArgumentException('Comments widget only accepts string action. Input was: ' . gettype($this->action));
        }

        $comments = Comment::find();
        if (!$this->mongo) {
            $comments->where(['type' => $this->type, 'pid' => $this->id, 'parentId' => null]);
        } else {
            $comments->where(['type' => $this->type, 'pidMongo' => $this->id, 'parentId' => null]);
        }
        $comments = $comments->orderBy('id DESC')->all();
        $model = new Comment();
        
        echo $this->render('list', [
            'id' => $this->id,
            'type' => $this->type,
            'comments' => $comments,
            'limit' => $this->limit,
            'model' => $model,
            'isMongo' => $this->mongo,
        ]);
        $view = $this->getView();
        Assets::register($view);

        $view->registerJs($this->js);
    }

    public static function getComment($item, $type, $limit, $nesting = 0, $isReply = false, $isNew = false)
    {
        $config['class'] = get_called_class();
        /* @var $widget Widget */
        $widget = Yii::createObject($config);
        static::$stack[] = $widget;

        echo $widget->render('comment', [
            'item' => $item,
            'type' => $type,
            'limit' => $limit,
            'nesting' => $nesting,
            'isReply' => $isReply,
            'isNew' => $isNew
        ]);
    }

    public function getJs()
    {
        $js[] = 'var urlAddComment = "'. \Yii::$app->UrlManager->createUrl('comment/add') .'";';
        $js[] = 'function getXmlHttp() {
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
            }';
        $js[] = 'function send(idcom, docom, like, up, down, ip) {
                var xmlhttp = getXmlHttp(); // Создаём объект XMLHTTP
                xmlhttp.open(\'POST\', \'/site/get-ajax\', true); // Открываем асинхронное соединение
                xmlhttp.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\'); // Отправляем кодировку
                xmlhttp.send("site=" + encodeURIComponent("http://'
            . \Yii::$app->params['appApi']
            . '/index.php?r=comment%2Flike&id=" + idcom) + encodeURIComponent("&do=" + docom) + encodeURIComponent("&ip=" + ip) ); // Отправляем POST-запрос
                xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
                    if (xmlhttp.readyState == 4) { // Ответ пришёл
                        if(xmlhttp.status == 200) { // Сервер вернул код 200 (что хорошо)
                            //console.log(xmlhttp.responseText);
                            if(xmlhttp.responseText < 0){
                                like.addClass("bad");
                            }else{
                                like.removeClass("bad");
                            }
                            like.text(xmlhttp.responseText);
                            up.text("");
                            down.text("");
                        }
                    }
                };
            }';
        $js[] = '$(document).on("click", ".reply", function(e){
                e.preventDefault();
                var form = $(this).parent().parent() ;
                var comment = $(this).parent().parent().parent();

                jQuery.ajax({
                    url: "' . Yii::$app->UrlManager->createUrl('comment/add') . '",
                    type: "POST",
                    dataType: "html",
                    data: form.serialize(),
                    async: false,
                    success: function(response) {
                        comment.after(response);
                        form.remove();
                    },
                    error: function(response) {}
                });
            });';
        return implode(PHP_EOL, $js);
    }
}

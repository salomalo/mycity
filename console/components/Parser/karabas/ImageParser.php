<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 22.09.2016
 * Time: 10:56
 */

namespace console\components\Parser\karabas;


use console\components\Parser\HttpParser;
use Exception;
use phpQuery;

class ImageParser extends HttpParser
{
    public $image;
    public $description;

    public static $sel = [
        'image' => 'html body div.wrapper div.content div.event-details div.max-wrap figure.photo img',
        'description' => 'html body div.wrapper div.content div.about-event div.max-wrap div.text',
    ];

    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];
        $blocks_img = $document_ru->find(self::$sel['image']);
        for ($i = 0; $i < count($blocks_img); $i++) {
            $img_link = pq($blocks_img->elements[$i]);
            $img_link = $img_link->attr('src');
            if ($img_link != null){
                $this->image = $img_link;
            }
        }

        $blocks_desc = $document_ru->find(self::$sel['description']);
        $blocks_desc->find('p.promotext')->remove();
        $this->description = $blocks_desc->text();

    }

    function download($url, $context = null)
    {
        $content = null;
        while (!$content) {
            try {
                $content = file_get_contents($url, null, $context);
                $content = phpQuery::newDocumentHTML($content, 'utf-8');
            } catch (Exception $e) {
                echo $e->getMessage();
                break;
            }
        }

        return $content;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 19.09.2016
 * Time: 17:57
 */

namespace console\components\Parser\kino;

use console\components\Parser\HttpParser;
use Exception;
use phpQuery;

class DateLinkParser extends HttpParser
{
    public static $sel = [
        'block_link' => 'div.week_calendar table a',
        'section_title' => 'a',
    ];

    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];

        $data = [];
        $blocks_ru = $document_ru->find(self::$sel['block_link']);

        for ($i = 0; $i < count($blocks_ru); $i++) {
            if ($i > 0) {
                $block_ru = pq($blocks_ru->elements[$i]);
                $data[$i]['link'] = 'http://kino.i.ua' . $block_ru->attr('href');
            } else{
                $data[$i]['link'] = $this->link . "&date=" . date('Y-m-d');
            }
        }
        $this->data = $data;

        return $this;
    }

    function download($url, $context = null)
    {
        $content = null;

        try {
            $content = file_get_contents($url, null, $context);
            $content = phpQuery::newDocumentHTML($content, 'utf-8');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $content;
    }
}
<?php

namespace console\components\Parser\karabas;

use console\components\Parser\HttpParser;
use Exception;
use phpQuery;

class CityParser extends  HttpParser
{
    public static $sel = [
        'block_col' => 'li.top_citys div.header-drop div.max-wrap div.city-cols div.col',
        'li' => 'ul.city-list li',
        'city_name' => 'a',
    ];

    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];
        $data = [];

        $blocks_ru = $document_ru->find(self::$sel['block_col']);
        $count = 0;
        for ($i = 0; $i < count($blocks_ru); $i++) {
            $block_ru = pq($blocks_ru->elements[$i]);
            $block_li = $block_ru->find(self::$sel['li']);

            for ($j = 0; $j < count($block_li); $j++) {
                $block_link = pq($block_li->elements[$j]);
                $block_link = $block_link->find(self::$sel['city_name']);

                $data[$count]['name'] = trim($block_link->text());
                $data[$count]['link'] = $block_link->attr('href');
                $count++;
            }
        }

        $this->data = $data;
        return $this;
    }

    function download($url, $context = null)
    {
        $content = null;
        $count = 0;

        while (!$content) {
            if ($count > 10) break;
            $count++;
            try {
                $content = file_get_contents($url, null, $context);
                $content = phpQuery::newDocumentHTML($content, 'utf-8');
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        return $content;
    }
}
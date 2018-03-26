<?php
namespace console\components\Parser\kino;

use console\components\Parser\HttpParser;
use Exception;
use phpQuery;

class LinkParser extends HttpParser
{
    public static $sel = [
        'block_link' => 'div.popup_mini div.content ul.list_simple li',
        'section_title' => 'a',
    ];

    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];
        $document_uk = $this->contents['uk'];

        $data = [];
        $blocks_ru = $document_ru->find(self::$sel['block_link']);
        $blocks_uk = $document_uk->find(self::$sel['block_link']);

        for ($i = 0; $i < count($blocks_ru); $i++) {
            $block_ru = pq($blocks_ru->elements[$i]);
            $block_uk = pq($blocks_uk->elements[$i]);

            $section_ru = trim($block_ru->find(self::$sel['section_title'])->text());
            $section_uk = trim($block_uk->find(self::$sel['section_title'])->text());

            $data[$i]['title'] = ['ru' => $section_ru, 'uk' => $section_uk];

            $section_ru = $block_ru->find(self::$sel['section_title'])->attr('href');

            $data[$i]['link'] = 'http://kino.i.ua'.$section_ru;
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
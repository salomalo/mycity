<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 13.09.2016
 * Time: 22:53
 */

namespace console\components\Parser\kino;


use console\components\Parser\HttpParser;
use Exception;
use phpQuery;

class SeanseParser extends HttpParser
{
    public static $sel = [
        'block_link' => 'table.list_titled tbody',
        'section_title' => 'tr th a',
        'block_cinema' => 'tr',
        'cinema_title' => 'a',
        'cinema_time' => 'a',
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

        for ($i = 0; $i < count($blocks_ru) / 2; $i++) {
            $block_ru = pq($blocks_ru->elements[$i * 2]);
            $block_uk = pq($blocks_uk->elements[$i * 2]);

            $section_ru = trim($block_ru->find(self::$sel['section_title'])->text());
            $section_uk = trim($block_uk->find(self::$sel['section_title'])->text());

            $data[$i]['title'] = ['ru' => $section_ru, 'uk' => $section_uk];

            $cinema_link = $block_ru->find(self::$sel['section_title'])->attr('href');
            $data[$i]['link'] = 'http://kino.i.ua' . $cinema_link;

            $block_ru = pq($blocks_ru->elements[$i * 2 + 1]);

            $tr_first_ru = $block_ru->find(self::$sel['block_cinema']);

            //получаем список кинотеатров по фильму
            for ($j = 0; $j < count($tr_first_ru); $j++) {
                $li_first_ru = pq($tr_first_ru->elements[$j]);

                $td_block = $li_first_ru->find('td');
                if (count($td_block) == 5) {

                    $td_second = pq($td_block->elements[1]);
                    $cinema_title = trim($td_second->find(self::$sel['cinema_title'])->text());
                    $data[$i]['cinema'][$j]['title'] = ['ru' => $cinema_title, 'uk' => $cinema_title];

                    $cinema_link = $td_second->find(self::$sel['cinema_title'])->attr('href');
                    $data[$i]['cinema'][$j]['link'] = 'http://kino.i.ua' . $cinema_link;

                    $td_third = pq($td_block->elements[2]);
                    $cinema_zal = trim($td_third->text());
                    $data[$i]['cinema'][$j]['zal'] = $cinema_zal;

                    $td_time_seanse = pq($td_block->elements[3]);
                    $cinema_time_a = $td_time_seanse->find(self::$sel['cinema_time']);
                    $times_array = array();
                    $times_array_3D = array();

                    //цикл по каждой ссылке времени
                    for ($k = 0; $k < count($cinema_time_a); $k++) {
                        $time_block = pq($cinema_time_a->elements[$k]);
                        $time_block_class = $time_block->attr('class');
                        $time_block = trim($time_block->text());
                        if ($time_block_class == 'ddd'){
                            $times_array_3D[] = $time_block;
                        } else {
                            $times_array[] = $time_block;
                        }

                    }
                    $data[$i]['cinema'][$j]['time'] = $times_array;
                    $data[$i]['cinema'][$j]['time_3D'] = $times_array_3D;

                    $td_price = pq($td_block->elements[4]);
                    $cinema_price = trim($td_price->text());
                    $data[$i]['cinema'][$j]['price'] = $cinema_price;
                }
            }
        }

        $this->data = $this->optimizeData($data);
        return $this;
    }

    private function optimizeData($data){
        $result = array();
        $i = 0;
        foreach ($data as $item){
            $result[$i]['title'] = $item['title'];
            $result[$i]['link'] = $item['link'];
            if(isset($item['cinema'])) {
                $k = 0;
                for ($j = 0; $j < count($item['cinema']); $j++ )
                {
                    if (isset($result[$i]['cinema'][$k])){
                        if ($result[$i]['cinema'][$k]['title'] == $item['cinema'][$j]['title']){
                            $result[$i]['cinema'][$k]['time'] = array_merge($result[$i]['cinema'][$k]['time'], $item['cinema'][$j]['time']);
                            $result[$i]['cinema'][$k]['time_3D'] = array_merge($result[$i]['cinema'][$k]['time_3D'], $item['cinema'][$j]['time_3D']);
                        } else {
                            $k++;
                            $result[$i]['cinema'][$k] = $item['cinema'][$j];
                        }

                    } else {
                        $result[$i]['cinema'][$k] = $item['cinema'][$j];
                    }
                }
            }

            $i++;
        }

        return $result;
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
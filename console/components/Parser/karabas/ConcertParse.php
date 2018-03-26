<?php

namespace console\components\Parser\karabas;

use console\components\Parser\HttpParser;
use Exception;
use phpQuery;

class ConcertParse extends  HttpParser
{

    public static $sel = [
        'data_block' => 'div.content-holder div.content-frame div.block-mini',
        'day' => 'div.box-date strong em',
        'month' => 'div.box-date span',
        'block_concert' => 'ul.information li',
        'title' => 'div.info h3.event_title a',
        'price' => 'div.block-price strong',
        'time' => 'div.block-time span',
        'name_bussines' => 'div.block-time a',
    ];

    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];
        $data = [];

        $blocks_ru = $document_ru->find(self::$sel['data_block']);
        $count = 0;
        for ($i = 0; $i < count($blocks_ru); $i++) {
            $block = pq($blocks_ru->elements[$i]);

            $day = trim($block->find(self::$sel['day'])->text());

            $date = $block->find(self::$sel['month']);
            $month = pq($date->elements[0]);
            $month = $month->text();
            $month = str_replace(array("\n", ",", " "), '', $month);

            $year = pq($date->elements[1]);
            $year = $year->text();

            $date = $year . '-' . $this->month_to_int[$month] . '-' . $day;

            $block_concert = $block->find(self::$sel['block_concert']);
            for ($j = 0; $j < count($block_concert); $j++) {
                $concert = pq($block_concert->elements[$j]);
                $data[$count]['date'] = str_replace(',', '-', $date);
                $data[$count]['date_end'] = $data[$count]['date'];

                $title = trim($concert->find(self::$sel['title'])->text());
                $title = str_replace(array('/','"'), '', $title);
                $data[$count]['title'] =  $title;
                //var_dump($title);
                $price = trim($concert->find(self::$sel['price'])->text());
                $data[$count]['price'] =  $price;

                $name_bussines = $concert->find(self::$sel['name_bussines'])->attr('href');
                $data[$count]['link_bussines'] = $name_bussines;

                $time = $concert->find(self::$sel['time']);
                $time->find('b')->remove();
                $time = trim($time->text());
                $time = str_replace(array(' ', ','), '', $time);
                $data[$count]['time'] =  array($time);

                $data[$count]['link_concert'] = $concert->find(self::$sel['title'])->attr('href');

                $count++;
            }
        }
        $this->data = $this->optimizeDate($data);
        return $this;
    }

    /**
     * Метод оптимизирует дату концертов. Если есть 2 одинаковых то изменяет дату окончания на самую большую
     */
    private function optimizeDate($data){
        $result = array();

        foreach ($data as $concert){
            $isNew = true;
            foreach ($result as &$value){
                if ($value['title'] == $concert['title']){
                    $value['date_end'] = $concert['date'];
                    if (!in_array($concert['time'][0], $value['time'])) {
                        $value['time'][] = $concert['time'][0];
                    }
                    $isNew = false;
                }
            }
            if ($isNew)
                $result[] = $concert;
        }

        return $result;
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

    private $month_to_int = [
        'января' => '01',
        'февраля' => '02',
        'марта' => '03',
        'апреля' => '04',
        'мая' => '05',
        'июня' => '06',
        'июля' => '07',
        'августа' => '08',
        'сентября' => '09',
        'октября' => '10',
        'ноября' => '11',
        'декабря' => '12'
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.09.2016
 * Time: 11:20
 */

namespace console\components\Parser\kino;


use console\components\Parser\HttpParser;
use console\models\Film;
use Exception;
use phpQuery;

class FilmParser extends HttpParser
{
    public $year;
    public $genre = array();
    public $country;
    public $director;
    public $actors;
    public $description;
    public $image_link;

    public static $col_name = [
        'year' => 'Год:',
        'genre' => 'Жанр:',
        'country' => 'Производство:',
        'director' => 'Режиссер:',
        'actors' => 'Актеры:',
        'description' => 'Описание:',
    ];

    public static $sel = [
        'block' => 'div.block_gamma_gradient div.content dl.preview_left dd p',
        'year' => 'a',
        'section_title' => 'tr th a',
        'block_cinema' => 'tr',
        'cinema_title' => 'a.cinema',
        'cinema_time' => 'a',
        'image' => 'div.block_gamma_gradient div.content dl.preview_left dt a.preview img',
    ];

    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];

        $this->image_link = $document_ru->find(self::$sel['image'])->attr("src");

        $block = $document_ru->find(self::$sel['block']);
        for ($i = 0; $i < count($block); $i++) {
            $p_first = pq($block->elements[$i]);

            $description_p = $p_first->find('b')->remove()->text();
            switch ($description_p) {
                case self::$col_name['year']:
                    $this->year = trim($p_first->find(self::$sel['year'])->text());
                    break;

                case self::$col_name['genre']:
                    $janr_block = $p_first->find('a');
                    for ($i = 0; $i < count($janr_block); $i++) {
                        $janr = pq($janr_block->elements[$i]);
                        $this->genre[] = mb_convert_case(trim($janr->text()), MB_CASE_TITLE, "UTF-8");
                    }
                    break;

                case self::$col_name['country']:
                    $this->country = trim($p_first->text());
                    break;

                case self::$col_name['director']:
                    $this->director = trim($p_first->text());
                    break;

                case self::$col_name['actors']:
                    $this->actors = trim($p_first->text());
                    break;

                case self::$col_name['description']:
                    $this->description = trim($p_first->text());
                    break;
            }
        }

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
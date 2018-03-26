<?php
namespace console\components\Parser\Hotline;

use console\components\Parser\HttpParser;
use phpQuery;

class LinkParser extends HttpParser
{
    /**
     * @return $this
     */
    public function parseData()
    {
        if (empty($this->contents['ru'])) {
            return $this;
        }

        $document = $this->contents['ru'];

        $last_page = $document->find('div.pager a.last')->attr('href');
        $last_page = $last_page ? (int)str_replace(['?', '&', 'p='], '', $last_page) : 0;

        $this->data = [];
        $page = 0;
        do {
            if ($page !== 0) {
                sleep(5);
                $document = $this->download("{$this->link}?p={$page}");
            }
            $page++;
            if (empty($document)) {
                continue;
            }
            $status = $this->parseDataPage($document);
        } while (($last_page >= $page) and $status);

        return $this;
    }

    private function parseDataPage($document)
    {
        /** @var $document \phpQueryObject */
        $links = $document->find('div#catalogue div.gd div.gd-box div.gd-info-cell > div.cell > b > a.g_statistic');

        //Обычные ссылки
        if (count($links) > 0) {
            foreach ($links as $link) {
                $this->data[] = 'http://hotline.ua' . pq($link)->attr('href');
            }
            return (count($this->data) === 0) ? false : true;
        } else {
            //Запчасти
            $links = $document->find('div.auto table tbody > tr > td > a');
            if (count($links) > 0) {
                foreach ($links as $link) {
                    $this->data[] = 'http://hotline.ua' . pq($link)->attr('href');
                }
                return (count($this->data) === 0) ? false : true;
            } else {
                echo 'Ссылки не найдены', PHP_EOL;
                return false;
            }
        }
    }
}

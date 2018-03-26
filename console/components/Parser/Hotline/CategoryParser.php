<?php
namespace console\components\Parser\Hotline;

use console\components\Parser\HttpParser;
use phpQuery;

class CategoryParser extends HttpParser
{
    public static $sel = [
        'blocks' => 'div.all-cat ul.coll li div.block',
        'expand_span' => 'div.c-titl span.exp',
        'section_title' => 'div.c-titl',
        'subsection_title' => 'span.s-titl',
        'ul_subsection' => 'ul:first > li',
        'ul_catalog' => 'ul:first > li',
        'catalog_link' => 'a:first',
    ];

    /**
     * @return $this
     */
    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];
        $document_uk = $this->contents['uk'];

        $data = [];
        $blocks_ru = $document_ru->find(self::$sel['blocks']);
        $blocks_uk = $document_uk->find(self::$sel['blocks']);

        //Разделы
        for ($i = 0; $i < count($blocks_ru->elements); $i++) {
            if (!isset($blocks_ru->elements[$i])) {
                continue;
            } elseif (!isset($blocks_uk->elements[$i])) {
                $blocks_uk->elements[$i] = $blocks_ru->elements[$i];
            }
            $block_ru = pq($blocks_ru->elements[$i]);
            $block_uk = pq($blocks_uk->elements[$i]);

            $block_ru->find(self::$sel['expand_span'])->remove();
            $block_uk->find(self::$sel['expand_span'])->remove();

            $section_ru = trim($block_ru->find(self::$sel['section_title'])->text());
            $section_uk = trim($block_uk->find(self::$sel['section_title'])->text());

            $data[$i] = ['title' => ['ru' => $section_ru, 'uk' => $section_uk]];

            $ul_first_ru = $block_ru->find(self::$sel['ul_subsection']);
            $ul_first_uk = $block_uk->find(self::$sel['ul_subsection']);

            //Подразделы
            for ($j = 0; $j < count($ul_first_ru->elements); $j++) {
                if (!isset($ul_first_ru->elements[$i])) {
                    continue;
                } elseif (!isset($ul_first_uk->elements[$i])) {
                    $ul_first_uk->elements[$i] = $ul_first_ru->elements[$i];
                }
                $li_first_ru = pq($ul_first_ru->elements[$j]);
                $li_first_uk = pq($ul_first_uk->elements[$j]);

                $subsection_ru = trim($li_first_ru->find(self::$sel['subsection_title'])->text());
                $subsection_uk = trim($li_first_uk->find(self::$sel['subsection_title'])->text());

                $data[$i]['subsection'][$j] = ['title' => ['ru' => $subsection_ru, 'uk' => $subsection_uk]];

                $ul_second_ru = $li_first_ru->find(self::$sel['ul_catalog']);
                $ul_second_uk = $li_first_uk->find(self::$sel['ul_catalog']);

                //Каталоги
                for ($k = 0; $k < count($ul_second_ru->elements); $k++) {
                    if (!isset($ul_second_ru->elements[$i])) {
                        continue;
                    } elseif (!isset($ul_second_uk->elements[$i])) {
                        $ul_second_uk->elements[$i] = $ul_second_ru->elements[$i];
                    }
                    $catalog_link_ru = pq($ul_second_ru->elements[$k])->find(self::$sel['catalog_link']);
                    $catalog_link_uk = pq($ul_second_uk->elements[$k])->find(self::$sel['catalog_link']);

                    $catalog_title_ru = $catalog_link_ru->text();
                    $catalog_title_uk = $catalog_link_uk->text();
                    $catalog_href = $catalog_link_ru->attr('href');

                    if ('/zapchasti/' === $catalog_href) {
                        $data[$i]['subsection'][$j]['catalog'] = $this->parseSpares("http://hotline.ua{$catalog_href}");
                    } else {
                        $data[$i]['subsection'][$j]['catalog'][$k] = [
                            'title' => ['ru' => $catalog_title_ru, 'uk' => $catalog_title_uk],
                            'url' => "http://hotline.ua{$catalog_href}"
                        ];
                    }
                }
            }
        }

        $this->data = $data;

        return $this;
    }

    private function parseSpares($url)
    {
        $this->log('Парсинг запчастей: ');
        $hotline = 'http://hotline.ua';
        $data = [];

        $manufacturer_doc = $this->download($url);

        if (empty($manufacturer_doc)) {
            return $data;
        }

        $manufacturer_links = $manufacturer_doc->find('div.auto-mark-list div.mark-item a');

        //Производители
        foreach ($manufacturer_links as $manufacturer_a) {
            $manufacturer_a = pq($manufacturer_a);
            $manufacturer_href = $hotline . $manufacturer_a->attr('href');

            $model_doc = $this->download($manufacturer_href);
            if (empty($model_doc)) {
                continue;
            }

            $model_links = $model_doc->find('div.auto-block div.model-item div.model-box a');

            //Модели
            foreach ($model_links as $model_a) {
                $model_a = pq($model_a);
                $model_href = $hotline . $model_a->attr('href');

                $msg = trim($manufacturer_a->text()) . ' ' . trim($model_a->text()) . ': ';
                $this->log($msg, false);

                $model_type_doc = $this->download($model_href);
                if (empty($model_type_doc)) {
                    continue;
                }

                $model_type_links = $model_type_doc->find('table.model-type tr td a');

                //Тип модели
                foreach ($model_type_links as $model_type_a) {
                    $model_type_a = pq($model_type_a);
                    $model_type_href = $hotline . $model_type_a->attr('href');
                    $this->log($model_type_a->text() . ' ', false);

                    //Получение списка запчастей
                    $limit = 5;
                    do {
                        if ($limit <= 0) {
                            break 2;
                        }
                        $limit--;

                        $spares_doc_ru = $this->download($model_type_href, stream_context_get_default([
                            'http' => ['method' => 'GET', 'header' => 'Cookie: language=ru']
                        ]));
                        $spares_doc_uk = $this->download($model_type_href, stream_context_get_default([
                            'http' => ['method' => 'GET', 'header' => 'Cookie: language=uk']
                        ]));

                        if (empty($spares_doc_ru) or empty($spares_doc_uk)) {
                            continue;
                        }

                        $spares_links_ru = $spares_doc_ru->find('div.parts-tree ul a');
                        $spares_links_uk = $spares_doc_uk->find('div.parts-tree ul a');
                    } while (count($spares_links_ru) !== count($spares_links_uk));

                    //Запчасти
                    for ($i = 0; $i < count($spares_links_ru); $i++) {
                        $spares_a_ru = pq($spares_links_ru->elements[$i]);
                        $spares_a_uk = pq($spares_links_uk->elements[$i]);

                        $spares_href = $hotline . $spares_a_ru->attr('href');

                        $spares_title_ru = trim($spares_a_ru->text());
                        $spares_title_uk = trim($spares_a_uk->text());

                        $data[] = ['title' => ['ru' => $spares_title_ru, 'uk' => $spares_title_uk], 'url' => $spares_href];
                    }
                }
                $this->log('');
            }
        }

        return $data;
    }
}

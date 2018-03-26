<?php

namespace common\helpers;

use yii;

class SearchHelper
{
    public static function getILike($searchText, $col)
    {
        $return = null;
        if (is_array($col)) {
            foreach ($col as $c) {
                $return[] = self::getILikeString($searchText, $c);
            }
        } else {
            $return = self::getILikeString($searchText, $col);
        }
        return $return;
    }

    public static function getILikeString($searchText, $col)
    {
        return ['~~*', $col, "%{$searchText}%"];
    }

    public static function getBusinessOrWhere($str)
    {
        $str_title = self::getModernTitle($str);

        $return[] = SearchHelper::getILike($str_title, '"business"."title"');
        $return = array_merge($return, SearchHelper::getILike($str, ['"business"."description"', '"business"."shortDescription"', '"business"."tags"']));

        return array_merge(['or'], $return);
    }

    public static function getActionOrWhere($str)
    {
        $str_title = self::getModernTitle($str);

        $return[] = SearchHelper::getILike($str_title, '"action"."title"');
        $return = array_merge($return, SearchHelper::getILike($str, ['"action"."description"', '"action"."tags"']));

        return array_merge(['or'], $return);
    }

    public static function modernSearchString($s)
    {
        $s = strip_tags($s);
        $s = str_replace(['"', '\'', '»', '«'], '_', $s);
        $s = trim($s);
        return $s;
    }

    private static function getModernTitle($title)
    {
        return str_replace([' ', '  ', '   ', PHP_EOL], '%', $title);
    }
}
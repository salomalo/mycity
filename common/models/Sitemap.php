<?php

namespace common\models;
use yii\db\ActiveRecord;

/**
 * Description of Sitemap
 *
 * @author dima
 */
class Sitemap extends ActiveRecord
{
    public static $priority = [
        '0.1' => '0.1',
        '0.2' => '0.2',
        '0.3' => '0.3',
        '0.4' => '0.4',
        '0.5' => '0.5',
        '0.6' => '0.6',
        '0.7' => '0.7',
        '0.8' => '0.8',
        '0.9' => '0.9',
        '1' => '1'
    ];
    public static $changefreq = [
        'always'    => 'always',
        'hourly'    => 'hourly',
        'daily'     => 'daily',
        'weekly'    => 'weekly',
        'monthly'   => 'monthly',
        'yearly'    => 'yearly',
        'never'     => 'never'
    ];
}

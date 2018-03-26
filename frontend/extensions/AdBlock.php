<?php

namespace frontend\extensions;

use common\models\Advertisement;
use frontend\extensions\ShowAdvertisement\ShowAdvertisement;

class AdBlock
{
    /**
     * @return bool
     */
    private static function isIntim()
    {
        return (bool)(defined('CATEGORYID') and (int)CATEGORYID === 6384);
    }

    /**
     * @return string
     */
    public static function getLeft()
    {
        $ads = ShowAdvertisement::widget(['position' => Advertisement::POS_SIDE_VERTICAL]);
        return $ads ? $ads : (self::isIntim() ? ''
            : '<noindex><div style="width: 239px;margin-bottom: 5px;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5655688774682149" data-ad-slot="5666586516" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div></noindex>');
    }

    /**
     * @return string
     */
    public static function getTop()
    {
        return '';
//        $ads = ShowAdvertisement::widget(['position' => Advertisement::POS_HEAD_HORIZONTAL]);
//        return $ads ? $ads : (self::isIntim() ? ''
//            : '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
//<!-- Адаптивный-Топ -->
//<ins class="adsbygoogle"
//     style="display:block"
//     data-ad-client="ca-pub-5655688774682149"
//     data-ad-slot="3987544118"
//     data-ad-format="auto"></ins>
//<script>
//(adsbygoogle = window.adsbygoogle || []).push({});
//</script>');
    }

    /**
     * @return string
     */
    public static function getQuad()
    {
        $ads = ShowAdvertisement::widget(['position' => Advertisement::POS_SIDE_SQUARE]);
        return $ads ? $ads : (self::isIntim() ? ''
            : '<noindex><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:250px;height:250px" data-ad-client="ca-pub-5655688774682149" data-ad-slot="9817584511"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div></noindex>');
    }
}

<?php

namespace console\controllers;

use common\models\Action;
use common\models\Afisha;
use common\models\Business;
use common\models\BusinessCategory;
use yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\console\Controller;
use common\models\Lang;
use common\models\City;
use common\models\SystemLog;
use yii\helpers\VarDumper;
use yii\mongodb\ActiveQuery as ActiveQueryMongoDb;

/**
 * Sitemap Controller
 */
class SitemapCitiesController extends Controller
{
    const BUSINESS = 'Business';
    const ADS = 'Ads';
    const POST = 'Post';
    const ACTION = 'Action';
    const AFISHA = 'Afisha';
    const SCHEDULE_KINO = 'ScheduleKino';
    const RESUME = 'Resume';
    const VACANTION = 'Vacantion';
    const WORK_RESUME = 'WorkResume';
    const WORK_VACANTION = 'WorkVacantion';
    const ABOUT_CITY = 'about-city';

    /** @var string $mainSite */
    public $mainSite = 'citylife';

    /** @var string $folder */
    public $folder = 'sitemap/';

    /** @var string $charset */
    public $charset = 'utf-8';

    /** @var $withArchive boolean */
    public $withArchive = false;

    /** @var int $limit */
    public $limit = 15000;

    /** @var string $allow_url */
    public $allow_url;

    /** @var string $home_sitemap */
    public $home_sitemap;

    /** @var string $home */
    public $home;

    /** @var $lastmod string */
    public $lastmod;

    /** @var $cityList City[] */
    public $cityList;

    /** @var $cityListPending City[] */
    public $cityListPending;

    /** @var $langList Lang[] */
    public $langList;

    /** @var $showLog boolean */
    public $showLog;

    public $news_priority = '0.8';
    public $stat_priority = '0.8';
    public $priority = '0.8';
    public $cat_priority = '1';

    public function init()
    {
        parent::init();

        $this->home = 'https://' . Yii::$app->params['appFrontend'] . '/';
        $this->home_sitemap = 'https://' . Yii::$app->params['appFrontend'] . '/' . $this->folder;

        $this->cityList = City::find()->where(['main' => City::ACTIVE])->orderBy('id')->all();
        $this->cityListPending = City::find()->where(['main' => City::PENDING])->orderBy('id')->all();

        $this->langList = Lang::find()->all();

        $this->limit /= !empty($this->langList) ? count($this->langList) : 1;

        $this->lastmod = date('Y-m-d');
    }

    public function actionIndex($showLog = false)
    {
        $this->showLog = (boolean)$showLog;

        $this->log('Do sitemap.');
        $allCities = array_merge($this->cityList, $this->cityListPending);

        $this->build_categories($allCities, [self::BUSINESS]);
        $this->build_categories($this->cityList, [self::AFISHA, self::ACTION, self::POST, self::VACANTION, self::RESUME]);
        //$this->build_business_map($allCities);
        $this->build_categories_main([self::POST, self::ACTION]);

        $this->build_other($this->cityList,[self::ABOUT_CITY, self::ADS, self::ACTION, self::AFISHA, self::POST, self::BUSINESS, self::VACANTION, self::RESUME]);
        $this->build_other_main();

        $countPostMain = $this->build_posts_main(self::POST);
        $countActionMain = $this->build_posts_main(self::ACTION);

        $r = [
            self::ACTION => null,
            self::AFISHA => null,
            self::SCHEDULE_KINO => null,
            self::POST => null,
            self::BUSINESS => null,
            self::WORK_RESUME => null,
            self::WORK_VACANTION => null,
        ];
        foreach ($r as $key => &$value) {
            $value = $this->build($key);
        }

        $this->build_index_city([self::AFISHA, self::POST, self::ACTION, self::BUSINESS, self::VACANTION, self::RESUME, self::ADS]);
        $this->build_index_main([self::POST, self::ACTION]);

        $systemLog = new SystemLog(['description' => [
            'Предприятия' => $r[self::BUSINESS],
            'Акции' => $r[self::ACTION] + $countActionMain,
            'Афиша' => $r[self::AFISHA] + $r[self::SCHEDULE_KINO],
            'Новости' => $r[self::POST] + $countPostMain,
            'Вакансии' => $r[self::WORK_VACANTION],
            'Резюме' => $r[self::WORK_RESUME],
        ]]);
        $systemLog->save();

        $this->log('sitemap stop.');
    }

    /**
     * @param $names
     * @return array
     */
    private function build_index_city($names)
    {
        $arrDomain = [];
        $cities = array_merge($this->cityList, $this->cityListPending);
        $cwd = getcwd();
        $date = date('Y-m-d');
        $baseUrl =  Yii::$app->params['appFrontend'] . "/$this->folder";

        /**
         * Проверяет наличие файла и возвращает блок xml для вставки в сайтмап
         *
         * @param string $subDomain
         * @param string $name
         * @param bool $iterable
         * @return string
         */
        $getSiteMap = function ($subDomain, $name, $iterable = false) use ($cwd, $date, $baseUrl) {
            $result = [];
            $urlBegin = "https://{$subDomain}.{$baseUrl}{$subDomain}/{$name}";
            $pathBegin = "$cwd/frontend/web/sitemap/{$subDomain}/{$name}";

            if ($iterable) {
                $i = 1;
                while (@file_exists("{$pathBegin}{$i}.xml")) {
                    $result[] = "\t<sitemap>";
                    $result[] = "\t\t<loc>{$urlBegin}{$i}.xml</loc>";
                    $result[] = "\t\t<lastmod>{$date}</lastmod>";
                    $result[] = "\t</sitemap>";
                    $i++;
                }
            } elseif (@file_exists("{$pathBegin}.xml")) {
                $result[] = "\t<sitemap>";
                $result[] = "\t\t<loc>{$urlBegin}.xml</loc>";
                $result[] = "\t\t<lastmod>{$date}</lastmod>";
                $result[] = "\t</sitemap>";
            }

            return implode(PHP_EOL, $result);
        };

        foreach ($cities as $city) {
            $map = [];
            $map[] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
            $map[] = "\t<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

            $map[] = $getSiteMap($city->subdomain, 'sitemap_Other');
            $map[] = $getSiteMap($city->subdomain, 'sitemap_Business_map');

            foreach ($names as $name) {
                $map[] = $getSiteMap($city->subdomain, "sitemap_{$name}");

                if ($name === 'Work') {
                    $map[] = $getSiteMap($city->subdomain, 'sitemap_WorkResume', true);
                    $map[] = $getSiteMap($city->subdomain, 'sitemap_WorkVacantion', true);
                } elseif ($name === 'Afisha') {
                    $map[] = $getSiteMap($city->subdomain, 'sitemap_Afisha', true);
                    $map[] = $getSiteMap($city->subdomain, 'sitemap_ScheduleKino', true);
                } else {
                    $map[] = $getSiteMap($city->subdomain, "sitemap_{$name}", true);
                }
            }

            $map[] = "</sitemapindex>";

            $sitemap = iconv($this->charset, "UTF-8//IGNORE", implode(PHP_EOL, array_filter($map)));

            $file = Yii::getAlias('@frontend/web/') . "sitemap_{$city->subdomain}.xml";
            $this->saveFile($file, $sitemap);

            $arrDomain[] = $city->subdomain;
        }
        return $arrDomain;
    }
    private function build_index_main($namesMain)
    {
        $date = date('Y-m-d');
        $map = [];
        $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $map[] = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $map[] = '<sitemap>';
        $map[] = "<loc>{$this->home_sitemap}{$this->mainSite}/sitemap_Other.xml</loc>";
        $map[] = "<lastmod>$date</lastmod>";
        $map[] = '</sitemap>';

        foreach ($namesMain as $name) {
            $map[] = '<sitemap>';
            $map[] = "<loc>{$this->home_sitemap}{$this->mainSite}/sitemap_{$name}.xml</loc>";
            $map[] = "<lastmod>{$this->lastmod}</lastmod>";
            $map[] = '</sitemap>';

            $map[] = $this->getFileListMain($name);
        }

        $map[] = '</sitemapindex>';

        $sitemap = iconv($this->charset, 'UTF-8//IGNORE', implode(PHP_EOL, $map));

        $file = Yii::getAlias('@frontend/web/') . "sitemap_{$this->mainSite}.xml";

        $this->saveFile($file, $sitemap);
    }

    /**
     * @param $arrDomain
     */
    public function setMainSitemap($arrDomain)
    {
        $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $map[] = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $date = date('Y-m-d');

        foreach ($arrDomain as $item) {
            $map[] = '<sitemap>';
            $map[] = "<loc>{$this->home_sitemap}sitemap_{$item}.xml</loc>";
            $map[] = "<lastmod>$date</lastmod>";
            $map[] = '</sitemap>';
        }

        $map[] = '</sitemapindex>';

        $sitemap = iconv($this->charset, 'UTF-8//IGNORE', implode("\n", $map));

        $file = Yii::getAlias('@frontend/web/') . '/sitemap_new.xml';
        $this->saveFile($file, $sitemap);
    }

    private function build_categories($cities, $names)
    {
        foreach($names as $name) {
            switch ($name) {
                case 'Vacantion':
                    $model = 'Work';
                    break;
                case 'Resume':
                    $model = 'Work';
                    break;
                case 'Ads':
                    $model = 'Product';
                    break;
                default:
                    $model = $name;
            }
            $p = "\\common\\models\\{$model}Category";
            $this->log($p, false);
            /* @var ActiveRecord[] $models */
            $models = $p::findAll(['sitemap_en' => 1]);

            foreach ($cities as $city) {
                $map = $this->generateMap($models, "{$name}/category", 'Categories', "{$city->subdomain}.");
                $folder = Yii::getAlias('@frontend/web/') . $this->folder . $city->subdomain ;
                $this->checkDir($folder);
                $file = "{$folder}/sitemap_{$name}.xml";
                $this->saveFile($file, $map);
            }
        }
    }
    private function build_categories_main($namesMain)
    {
        $front = Yii::$app->params['appFrontend'];
        foreach($namesMain as $name) {
            $name_lower = strtolower($name);
            $map = [];
            $p = "\\common\\models\\{$name}Category";
            $models = $p::findAll(['sitemap_en' => 1]);

            $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $map[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($models as $item) {
                foreach ($this->langList as $lang){
                    $link = Url::to(["/$name_lower/index", 'pid' => $item->url]);

                    $link = str_replace(['/ruhttp://', '/ukhttp://', '/ruhttps://', '/ukhttps://'], 'https://', $link);
                    $link = str_replace($front, "{$front}/{$lang->url}", $link);

                    $map[] = "\t<url>";
                    $map[] = "\t\t<loc>{$link}</loc>";
                    $map[] = $item->dateUpdate ? "\t\t<lastmod>{$item->dateUpdate}</lastmod>" : null;
                    $map[] = $item->sitemap_changefreq ? "\t\t<changefreq>{$item->sitemap_changefreq}</changefreq>" : null;
                    $map[] = $item->sitemap_priority ? "\t\t<priority>{$item->sitemap_priority}</priority>" : null;
                    $map[] = "\t</url>";
                }
            }

            $map[] = "</urlset>";

            $folder = Yii::getAlias('@frontend/web/') . "{$this->folder}{$this->mainSite}";
            $this->checkDir($folder);
            $this->saveFile("{$folder}/sitemap_{$name}.xml", implode(PHP_EOL, $map));
        }
    }

    /**
     * @param $name
     * @return int
     */
    private function build_posts_main($name)
    {
        $front = Yii::$app->params['appFrontend'];
        $now = date('Y-m-d H:i:s');
        $andWhere = [];
        $alias = strtolower($name);
        $p = "\\common\\models\\{$name}";
        $m = new $p;

        $mod = $m::find();
        if ($name == 'Action') {
            $where = ['action.sitemap_en' => 1];
            if(!$this->withArchive){
                $andWhere = ['>', 'dateEnd', $now];
            }
        }

        if ($name == 'Post') {
            $where = ['sitemap_en' => 1, 'allCity' => true];
        }

        $mod->where($where);
        if (!empty($andWhere)) {
            $mod->andWhere($andWhere);
        }
        $count = $mod->count();

        $pages = ceil($count/$this->limit);

        $this->log($name);
        for ($i = 1; $i <= $pages; $i++) {
            $offset = ($i-1)*$this->limit;
            $model = $mod->where($where);
            if (!empty($andWhere)) {
                $model = $model->andWhere($andWhere);
            }
            $model = $model->limit($this->limit)->offset($offset)->orderBy('id')->all();

            $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $map[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $subDomain = '';

            foreach ($model as $item) {
                $link_item = Url::to(["/{$alias}/{$item->id}-{$item->url}"]);
                $link_item = str_replace(['/ruhttp://', '/ukhttp://', '/ruhttps://', '/ukhttps://'], 'https://', $link_item);

                if (($name === 'Action') and isset($item->companyName) and isset($item->companyName->city)) {
                    $subDomain = "{$item->companyName->city->subdomain}.";
                }

                foreach ($this->langList as $lang) {
                    $map[] = "\t<url>";

                    $link = str_replace($front, "{$subDomain}{$front}/{$lang->url}", $link_item);
                    $map[] = "\t\t<loc>$link</loc>";

                    $map[] = $item->dateUpdate ? "\t\t<lastmod>$item->dateUpdate</lastmod>" : null;
                    $map[] = $item->sitemap_changefreq ? "\t\t<changefreq>{$item->sitemap_changefreq}</changefreq>" : null;
                    $map[] = $item->sitemap_priority ? "\t\t<priority>{$item->sitemap_priority}</priority>" : null;

                    $map[] = "\t</url>";
                }
                $this->log('.', false);
            }

            $map[] = "</urlset>";

            $file = Yii::getAlias('@frontend/web/') . "{$this->folder}{$this->mainSite}/sitemap_{$name}{$i}.xml";
            $this->saveFile($file, implode(PHP_EOL, $map));
            $this->log('');
        }
        return count($this->langList) * $count;
    }

    /**
     * @param $cities
     * @param $arr
     */
    private function build_other($cities, $arr)
    {
        $front = Yii::$app->params['appFrontend'];
        foreach ($cities as $city) {
            $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $map[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            $model = City::findOne(['id' => $city->id]);
            $subDomain = "{$model->subdomain}.";

            foreach ($arr as $item) {
                $link_item = Url::to(['/' . strtolower($item)]);
                $link_item = str_replace(['/ruhttp://', '/ukhttp://', '/ruhttps://', '/ukhttps://'], 'https://', $link_item);
                foreach ($this->langList as $lang) {
                    $link = str_replace($front, "{$subDomain}{$front}/{$lang->url}", $link_item);

                    $map[] = "\t<url>";
                    $map[] = "\t\t<loc>$link</loc>";
                    $map[] = "\t</url>";
                    $this->log('.', false);
                }
            }
            $map[] = "</urlset>";
            $file = Yii::getAlias('@frontend/web/') . "{$this->folder}{$city->subdomain}/sitemap_Other.xml";
            $this->saveFile($file, implode(PHP_EOL, $map));
            $map = [];
        }
        $this->log('');
    }
    private function build_other_main()
    {
        $front = Yii::$app->params['appFrontend'];
        $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $map[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->langList as $lang){
            $link_lang = str_replace($front, "{$front}/{$lang->url}", Url::to(['/']));
            $link_lang = str_replace(['/ruhttp://', '/ukhttp://', '/ruhttps://', '/ukhttps://'], 'https://', $link_lang);
            foreach ($this->cityList as $city){
                $link = str_replace(['http://', 'https://'], "https://{$city->subdomain}.", $link_lang);
                $map[] = "\t<url>";
                $map[] = "\t\t<loc>$link</loc>";
                $map[] = "\t</url>";
            }
            foreach (['/action', '/post'] as $item) {
                $link = str_replace($front, "{$front}/$lang->url", Url::to([$item]));
                $link = str_replace(['/ruhttp://', '/ukhttp://', '/ruhttps://', '/ukhttps://'], 'https://', $link);
                $map[] = "\t<url>";
                $map[] = "\t\t<loc>$link</loc>";
                $map[] = "\t</url>";
            }
        }
        $map[] = '</urlset>';

        $file = Yii::getAlias('@frontend/web/') . "{$this->folder}{$this->mainSite}/sitemap_Other.xml";
        $this->saveFile($file, implode(PHP_EOL, $map));
    }

    /**
     * @param $name
     * @return string
     */
    public function getFileListMain($name)
    {
        $map = [];
        $model = $name;

        $p = '\\common\\models\\'.$model;
        $m = new $p;

        $mod = $m::find();
        $where = [];

        if ($name === 'Action') {
            $where = ['action.sitemap_en' => 1];
        }
        if ($name === 'Post') {
            $where = ['sitemap_en' => 1, 'allCity' => true];
        }

        $count = $mod->andWhere($where)->count();

        if($count >= $this->limit){
            for($i = 1; $i <= ceil($count/$this->limit); $i++){
                $map[] = '<sitemap>';
                $map[] = "<loc>{$this->home_sitemap}{$this->mainSite}/sitemap_{$name}{$i}.xml</loc>";
                $map[] = "<lastmod>{$this->lastmod}</lastmod>";
                $map[] = '</sitemap>';
            }
            return implode(PHP_EOL, $map);
        }
        else{
            return "<sitemap>\n<loc>{$this->home_sitemap}{$this->mainSite}/sitemap_{$name}1.xml</loc>\n<lastmod>{$this->lastmod}</lastmod>\n</sitemap>\n";
        }

    }

    /**
     * @param $string
     * @param bool $eol
     * @internal param bool $fail
     */
    private function log($string, $eol = true)
    {
        if ($this->showLog) {
            echo $string, $eol ? PHP_EOL : '';
        }
    }

    /**
     * @param $model
     * @param $city
     * @param $where
     * @param $andwhere
     * @return ActiveQuery|ActiveQueryMongoDb
     */
    private function getItemsFromModel($model, $city, $where, $andwhere)
    {
        /* @var $model ActiveRecord|\yii\mongodb\ActiveRecord */
        $items = $model::find();

        /* @var $items ActiveQuery|ActiveQueryMongoDb */
        if ($model::className() === Afisha::className()){
            $items->leftJoin('business', 'business.id = ANY(afisha."idsCompany")')
                ->where(['business."idCity"' => $city])
                ->groupBy('afisha.id')->orderBy('afisha.id');
        } elseif ($model::className() === Action::className()) {
            $items->leftJoin('business', 'business.id = action."idCompany"')
                ->where(['business."idCity"' => $city])
                ->groupBy('action.id')->orderBy('action.id');
        } elseif ($model::className() === Business::className()) {
            $items->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
                ->where(['idCity' => $city])
                ->andWhere(['business_category.sitemap_en' => 1])
                ->groupBy('business.id')->orderBy('business.id');
        } elseif ($items::className() === ActiveQueryMongoDb::className()) {
            $items->where(['idCity' => $city])->orderBy('id');
        } else {
            $items->where(['idCity' => $city])->groupBy('id')->orderBy('id');
        }
        if ($where) {
            $items->andWhere($where);
        }
        if ($andwhere) {
            $items->andWhere($andwhere);
        }
        return $items;
    }

    /**
     * @param $model ActiveRecord[]
     * @param $name string
     * @param $realname string
     * @param string|null $sd
     * @return string
     * @internal param null $action
     */
    private function generateMap($model, $name, $realname, $sd = null)
    {
        $frontend = Yii::$app->params['appFrontend'];
        $map[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $map[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($model as $item){
            $field = $this->getItemField($item, $realname, $sd);
            foreach ($this->langList as $lang){
                $url = implode(['/', strtolower($name), '/', $field['id'], ($field['id'] ? '-' : null), $field['url']]);
                $link = str_replace(['/ruhttp://', '/ukhttp://', '/ruhttps://', '/ukhttps://'], 'https://', Url::to([$url]));
                $link = str_replace($frontend, "{$field['subdomain']}{$frontend}/{$lang->url}", $link);
                $map[] = "\t<url>";
                $map[] = "\t\t<loc>$link</loc>";
                $map[] = $field['dateUpdate'] ? ("\t\t<lastmod>{$field['dateUpdate']}</lastmod>") : null;
                $map[] = $field['changefreq'] ? ("\t\t<changefreq>{$field['changefreq']}</changefreq>") : null;
                $map[] = $field['priority'] ? ("\t\t<priority>{$field['priority']}</priority>") : null;
                $map[] = "\t</url>";
            }
            $this->log('.', false);
        }
        $map[] = "</urlset>";
        $this->log('');

        return implode(PHP_EOL, $map);
    }

    /**
     * @param $item
     * @param $name
     * @param null $sd
     * @return array
     */
    private function getItemField($item, $name, $sd = null)
    {
        switch ($name) {
            case 'ScheduleKino':
                $return = [
                    'title' => $item->afisha->title,
                    'id' => $item->afisha->id,
                    'url' => $item->afisha->url,
                    'subdomain' => ($sd and empty($item->city)) ? $sd : (!empty($item->city) ? ($item->city->subdomain . '.') : ''),
                    'dateUpdate' => isset($item->afisha->dateUpdate) ? $item->afisha->dateUpdate : null,
                    'changefreq' => isset($item->afisha->sitemap_changefreq) ? $item->afisha->sitemap_changefreq : null,
                    'priority' => isset($item->afisha->sitemap_priority) ? $item->afisha->sitemap_priority : null,
                ];
                break;
            case 'Ads':
                $return = [
                    'id' => $item->_id,
                    'subdomain' => ($sd and empty($item->city)) ? $sd : (!empty($item->city) ? ($item->city->subdomain . '.') : ''),
                ];
                break;
            case 'Afisha':
                /** @var $item Afisha */
                $return = [
                    'id' => $item->id,
                    'subdomain' => $item->companys[0]->city->subdomain . '.',
                ];
                break;
            case 'Action':
                /** @var $item Action */
                $return = [
                    'id' => $item->id,
                    'subdomain' => $item->companyName->city->subdomain . '.',
                ];
                break;
            case 'Categories':
                $return = [
                    'id' => null,
                    'subdomain' => $sd,
                ];
                break;
            default:
                $return = [
                    'id' => $item->id,
                    'subdomain' => ($sd and empty($item->city)) ? $sd : (!empty($item->city) ? ($item->city->subdomain . '.') : ''),
                ];
                break;
        }
        if ($name !== 'ScheduleKino') {
            $return['title'] = $item->title;
            $return['url'] = $item->url;
            $return['dateUpdate'] = isset($item->dateUpdate) ? $item->dateUpdate : null;
            $return['changefreq'] = isset($item->sitemap_changefreq) ? $item->sitemap_changefreq : null;
            $return['priority'] = isset($item->sitemap_priority) ? $item->sitemap_priority : null;
        }
        return $return;
    }

    /**
     * @param $name
     * @return array
     */
    private function getParams($name)
    {
        switch ($name) {
            case 'ScheduleKino':
                $arr = [
                    'mapname' => 'Afisha',
                    'where' => null,
                    'andwhere' => (!$this->withArchive) ? "\"dateEnd\" > '$this->lastmod'" : null,
                ];
                break;
            case 'Ads':
                $arr = [
                    'mapname' => $name,
                    'where' => null,
                    'andwhere' => null,
                ];
                break;
            case 'Afisha':
                $arr = [
                    'mapname' => $name,
                    'where' => ['isFilm' => 0, '"afisha"."sitemap_en"' => 1],
                    'andwhere' => (!$this->withArchive) ? "\"dateEnd\" > '$this->lastmod'" : null,
                ];
                break;
            case 'Action':
                $arr = [
                    'mapname' => $name,
                    'where' => ['"action"."sitemap_en"' => 1],
                    'andwhere' => (!$this->withArchive) ? "\"dateEnd\" > '$this->lastmod'" : null,
                ];
                break;
            case 'WorkResume':
                $arr = [
                    'mapname' => 'resume',
                    'where' => ['sitemap_en' => 1],
                    'andwhere' => null,
                ];
                break;
            case 'WorkVacantion':
                $arr = [
                    'mapname' => 'vacantion',
                    'where' => ['sitemap_en' => 1],
                    'andwhere' => null,
                ];
                break;
            case 'Business':
                $arr = [
                    'mapname' => $name,
                    'where' => ['"business"."sitemap_en"' => 1],
                    'andwhere' => null,
                ];
                break;
            default:
                $arr = [
                    'mapname' => $name,
                    'where' => ['sitemap_en' => 1],
                    'andwhere' => null,
                ];
        }
        $arr['name'] = $name;
        $arr['path'] = '\\common\\models\\'.$name;
        $arr['alias'] = Yii::getAlias('@frontend/web/');
        return $arr;
    }

    /**
     * @param $name
     * @return int
     */
    private function build($name)
    {
        $params = $this->getParams($name);
        $result = 0;
        $class = new $params['path'];

        if (self::BUSINESS === $name) {
            $cities = array_merge($this->cityList, $this->cityListPending);
        } else {
            $cities = $this->cityList;
        }
        foreach ($cities as $city) {
            $items = $this->getItemsFromModel($class, $city->id, $params['where'], $params['andwhere']);
            if ($items::className() === ActiveQueryMongoDb::className()) {
                $count = $items->count();
                $pages = ceil($count / $this->limit);

                for ($i = 1; $i <= $pages; $i++) {
                    $offset = ($i - 1) * $this->limit;
                    $model = $items->limit($this->limit)->offset($offset)->all();
                    $result += $this->doSitemap($model, $params, $city, $name, $i);
                }
            } else {
                $i = 1;
                foreach ($items->batch($this->limit) as $models) {
                    $result += $this->doSitemap($models, $params, $city, $name, $i);
                    $i++;
                }
            }
        }
        return $result;
    }

    /**
     * @param $model ActiveRecord[]
     * @param $params array
     * @param $city City
     * @param $name string
     * @param $i int
     * @return int|void
     */
    private function doSitemap($model, $params, $city, $name, $i)
    {
        if ($count = count($model)) {
            $this->log($model[0]::className() . " $city->subdomain $count");
        }
        $map = $this->generateMap($model, $params['mapname'], $params['name']);

        $folder = "{$params['alias']}{$this->folder}{$city->subdomain}";
        $this->checkDir($folder);

        $this->saveFile("{$folder}/sitemap_{$name}{$i}.xml", $map);

        return count($model) * count($this->langList);
    }

    public function actionSetAll()
    {
        Business::updateAll(['sitemap_en' => 1]);
    }

    private function saveFile($filename, $content)
    {
        $handler = @fopen($filename, 'wb+');
        if ($handler) {
            @fwrite($handler, $content);
            @fclose($handler);
        }
    }

    private function build_business_map($cities)
    {
        $models = BusinessCategory::find()->where(['sitemap_en' => 1])->all();
        foreach ($cities as $city) {
            $map = $this->generateMap($models, '/map/business/category', 'Categories', "{$city->subdomain}.");
            $folder = Yii::getAlias('@frontend/web/') . "{$this->folder}{$city->subdomain}";

            $this->checkDir($folder);
            $this->saveFile("$folder/sitemap_Business_map.xml", $map);
        }
    }

    private function checkDir($path)
    {
        if (!@file_exists($path)) {
            @mkdir($path, 0777);
        }
    }
}

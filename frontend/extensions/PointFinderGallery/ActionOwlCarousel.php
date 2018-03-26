<?php
namespace frontend\extensions\PointFinderGallery;

use common\models\Action;
use yii;
use yii\base\Widget;

class ActionOwlCarousel extends Widget
{
    public $limit = 12;
    public $title;

    public function init()
    {
        $this->title = 'Акции';
        parent::init();
    }

    public function run()
    {
        $query = Action::find()
            ->with(['companyName', 'category'])
            ->limit($this->limit)
            ->where(['not', ['action.image' => '']])
            ->andWhere(['>=', 'dateEnd', date('Y-m-d H:i:s')])
            ->andWhere(['<=', 'dateStart', date('Y-m-d 23:59:59')])
            ->orderBy(['dateEnd' => SORT_ASC]);

        if ($city = Yii::$app->request->city) {
            $query->leftJoin('business', 'business.id = action."idCompany"')
                ->andWhere(['business."idCity"' => $city->id]);
        }

        /** @var Action[] $actions */
        $actions = $query->all();

        /**
         * @param $string string
         * @param $limit integer
         * @return string
         */
        $crop = function ($string, $limit) {
            if (mb_strlen($string, 'utf-8') > $limit) {
                $string = mb_substr($string, 0, $limit, 'utf-8');
                $lastSpace = mb_strrpos($string, ' ', 'utf-8');
                $string = mb_substr($string, 0, $lastSpace, 'utf-8');
                $string .= '...';
            }
            return $string;
        };

        /**
         * @param $models Action[]
         * @return array
         */
        $convertActionToData = function ($models) use ($crop) {
            $data = [];
            foreach ($models as $model) {
                $item['title'] = $crop($model->title, 20);
                $item['url'] = $model->getRoute();

                $item['image'] = [];
                $item['image']['src'] = Yii::$app->files->getUrl($model, 'image');

                $item['image']['bottom'] = [];
                $item['image']['bottom']['left'] = '';
                $item['image']['bottom']['right'] = $model->getRemainingTime($model->dateStart, $model->dateEnd)['left'];

                $item['detail'] = [];
                $item['detail']['second'] = $model->companyName ? $model->companyName->title : null;
                $item['detail']['third'] = $crop(strip_tags($model->description), 60);

                $data[] = $item;
            }

            return $data;
        };

        $data = $convertActionToData($actions);

        return $data ? $this->render('home',['data' => $data]) : null;
    }
}

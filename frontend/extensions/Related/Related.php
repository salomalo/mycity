<?php

namespace frontend\extensions\Related;

use common\models\Business;
use common\models\Post;
use yii;
use yii\base\Widget;

/**
 * Description of Related
 *
 * @author dima
 */
class Related extends Widget
{
    public $idCategory = null;
    public $idModel;
    public $model;
    public $idCity;
    public $city;
    public $limit = 3;
    public $view = '';
    public $title = '';
    public $time;

    public function run()
    {
        if (!(!empty($this->idCategory) or $this->model === Post::className()) or empty($this->model)) {
            return false;
        }

        $model = new $this->model;

        $models = $model::find();
        if (is_int($this->idModel)) {
            $arr = ['product', 'ads'];
            if (in_array($this->view, $arr)) {
                $table = $model::collectionName();
            } else {
                $table = $model::tableName();
            }
            $models->where($table . '.id <> :id', [':id' => $this->idModel]);
        }

        if ($this->idCategory) {
            if (!is_array($this->idCategory)) {
                $models->andWhere(['idCategory' => $this->idCategory]);
            } else {
                $list = $model->php_to_postgres_array($this->idCategory);
                $models->andWhere("\"idCategories\" && '" . $list . "'");
            }
        }

        if (in_array($this->view, ['business', 'post', 'vacantion', 'resume'])) {
            if ($this->idCity) {
                $models->andWhere(['idCity' => $this->idCity]);
            }
        }

        if ($this->view === 'post_super_list') {
            $models->andWhere(['status' => Post::TYPE_PUBLISHED]);
            if (!empty(Yii::$app->params['SUBDOMAINTITLE'])) {
                $models->andWhere(['idCity' => Yii::$app->params['SUBDOMAINID']]);
            } else {
                $models->andWhere(['is', 'idCity', null]);
            }
            $models->orderBy('id DESC');
        } elseif ($this->view === 'action_super_list') {
            if ($this->idCity) {
                $models->leftJoin('business', 'action."idCompany" = business.id');
                $models->andWhere(['business."idCity"' => $this->idCity]);

                $now = new \DateTime();
                $now->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));
                $now->setTimestamp(strtotime(date('Y-m-d')));

                $models->andWhere('"dateEnd" >= ' . "'" . $now->format('Y-m-d 00:00:00') . "'");
                $date2 = $now->modify('+1 day');
                $models->andWhere('"dateStart" <= ' . "'" . $date2->format('Y-m-d 00:00:00') . "'");
            }
        } elseif ($this->view === 'afisha') {
            $now = ($this->time) ? date('Y-m-d', strtotime($this->time)) : date('Y-m-d');
            $start = new \DateTime($now);
            $date2 = $start->modify('+1 day');
            $models->andWhere('"dateStart" <= ' . "'" . $date2->format('Y-m-d') . "'");
            $models->andWhere('"dateEnd" >= ' . "'" . $now . "'");
            if ($this->idCity) {
                $models->leftJoin('business', 'business.id = ANY (afisha."idsCompany")')
                    ->andWhere('business."idCity" = ' . $this->idCity);
            }
        } elseif ($this->view === 'business') {
//            $models->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
//                ->andWhere(['business_category.sitemap_en' => 1])
            $models->orderBy('ratio DESC');
        }

        $models = $models->limit($this->limit)->all();

        if (!$models) {
            return false;
        }

        return $this->render($this->view, [
            'models' => $models, 'title' => $this->title, 'city' => $this->city, 'idCategory' => $this->idCategory,
        ]);
    }
}

<?php
namespace backend\extensions\NotCheckedAfish;

use backend\models\Admin;
use common\models\Afisha;
use Yii;
use yii\base\Widget;

class NotCheckedAfish extends Widget
{
    public function run()
    {
        $admin = Yii::$app->user->identity;
        $not_checked = ['all' => null, 'film' => null, 'afisha' => null];
        $cookies = Yii::$app->request->cookies;

        if ($admin->level === Admin::LEVEL_SUPER_ADMIN) {
            $not_checked['all'] = Afisha::find()
                ->where(['isChecked' => 0])
                ->count();

            $not_checked['film'] = Afisha::find()
                ->where(['isChecked' => 0, 'isFilm' => 1])
                ->count();

            $query_afisha = Afisha::find()
                ->leftJoin('business', 'business.id = ANY(afisha."idsCompany")')
                ->where(['afisha.isChecked' => 0, 'afisha.isFilm' => 0]);
            if ($cookies->has('SUBDOMAINID')) {
                $query_afisha->andWhere(['business."idCity"' => $cookies->get('SUBDOMAINID')->value]);
            }

            $not_checked['afisha'] = $query_afisha->count();
        } elseif ($admin->citiesId) {
            $not_checked['film'] = Afisha::find()
                ->where(['afisha.isChecked' => 0, 'afisha.isFilm' => 1])
                ->count();

            $query_afisha = Afisha::find()
                ->leftJoin('business', 'business.id = ANY(afisha."idsCompany")')
                ->where(['afisha.isChecked' => 0, 'afisha.isFilm' => 0, 'business.idCity' => $admin->citiesId]);

            if ($cookies->has('SUBDOMAINID')) {
                $query_afisha->andWhere(['business."idCity"' => $cookies->get('SUBDOMAINID')->value]);
            }

            $not_checked['afisha'] = $query_afisha->count();
        }
        $not_checked['all'] = $not_checked['afisha'] + $not_checked['film'];

        return $this->render('index', ['countNotification' => $not_checked]);
    }
}

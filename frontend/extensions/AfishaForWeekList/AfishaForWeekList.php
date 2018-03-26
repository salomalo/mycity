<?php

namespace frontend\extensions\AfishaForWeekList;

use common\models\Afisha;
use frontend\extensions\MyLinkPager\MyLinkPager;
use Yii;
use yii\base\Widget;
use yii\helpers\VarDumper;

class AfishaForWeekList extends Widget
{
    /**
     * @var Afisha[]
     */
    public $models;

    /**
     * @var Pagination
     */
    public $pages;

    public function run()
    {
        $lastcat = null;
        $showCatFilm = 0;

        foreach ($this->models as $model) {
            if ($model->isFilm) {
                echo $this->render('_afisha_short_film', [
                    'model' => $model,
                    'showCatFilm' => $showCatFilm++,
                ]);
            } else {
                if (!$lastcat or ($lastcat !== $model->idCategory)) {
                    $lastcat = $model->idCategory;
                    $showCat = true;
                } else {
                    $showCat = false;
                }

                echo $this->render('_afisha_short', [
                    'model' => $model,
                    'showCat' => $showCat,
                ]);
            }
        }

        echo MyLinkPager::widget(['pagination' => $this->pages]);
    }
}
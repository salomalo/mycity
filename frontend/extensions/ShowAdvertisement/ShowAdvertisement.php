<?php
namespace frontend\extensions\ShowAdvertisement;

use common\models\Advertisement;
use Yii;
use yii\base\Widget;

class ShowAdvertisement extends Widget
{
    public $position;

    public function run()
    {
        $now = date('Y-m-d');
        if ($city = Yii::$app->request->city) {
            $model = Advertisement::find()
                ->where(['<=', 'date_start', $now])->andWhere(['>=', 'date_end', $now])
                ->andWhere(['status' => Advertisement::STATUS_ACTIVE])
                ->andWhere(['position' => $this->position])
                ->andWhere(['city_id' => $city->id])
                ->one();

            if ($model) {
                return $this->render('main', ['model' => $model]);
            }
        }

        return null;
    }
}

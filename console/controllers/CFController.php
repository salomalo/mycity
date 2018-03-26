<?php

namespace console\controllers;

use common\models\BusinessCustomFieldDefaultVal;
use common\models\BusinessCustomFieldValue;
use yii\console\Controller;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class CFController extends Controller {

    public function actionParseToFloat()
    {
        $classes = [BusinessCustomFieldValue::className(), BusinessCustomFieldDefaultVal::className()];
        foreach ($classes as $class) {
            /** @var ActiveQuery $query */
            $query = $class::find()->where(['value_numb' => null]);
            echo "All $class: ", $query->count();
            /** @var ActiveRecord $models */
            foreach ($query->batch() as $models) {
                foreach ($models as $model) {
                    echo $model->save() ? '.' : ',';
                }
                echo PHP_EOL;
            }
        }
    }
}

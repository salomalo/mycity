<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 20.07.16
 * Time: 12:33
 */
namespace console\controllers;

use common\models\Action;
use common\models\Ads;
use common\models\Afisha;
use common\models\Business;
use common\models\File;
use common\models\Post;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class FileController extends Controller
{
    public function actionFixImages()
    {
        echo 'add image to file', PHP_EOL;
        $models = [
            Action::className() => ['image' => File::TYPE_ACTION],
            Post::className() => ['image' => File::TYPE_POST],
            Afisha::className() => ['image' => File::TYPE_AFISHA],
            Business::className() => ['image' => File::TYPE_BUSINESS],
        ];
        /**
         * @var ActiveRecord|string $model
         * @var array $fields
         */
        foreach ($models as $model => $fields) {
            echo "$model ";
            foreach ($fields as $field => $type) {
                $files = File::find()->select('name')->where(['type' => $type])->column();
                $images = $model::find()->select([$field, 'id'])->orderBy('id')->indexBy('id')->column();

                foreach ($images as $pid => $image) {
                    if (!empty($image)) {
                        if (!in_array($image, $files)) {
                            $file = new File([
                                'type' => (string)$type,
                                'pid' => (string)$pid,
                                'size' => rand(1000, 25000),
                                'name' => (string)$image,
                            ]);
                            if ($file->save()) {
                                echo '.';
                            } else {
                                var_dump($file->errors);
                            }
                        }
                    }
                }
                echo PHP_EOL;
            }
        }

        echo 'del image from file', PHP_EOL;
        $types = [
            File::TYPE_ACTION => Action::className(),
            File::TYPE_AFISHA => Afisha::className(),
            File::TYPE_POST => Post::className(),
            File::TYPE_BUSINESS => Business::className(),
            File::TYPE_ADS => Ads::className(),
        ];
        foreach ($types as $type => $model) {
            echo "$model ";
            $files = File::find()->select(['id', 'pid'])->where(['pidMongo' => null, 'type' => $type])->groupBy('id')->orderBy('id')->asArray()->all();
            if ($model === Ads::className()) {
                $model_ids = $model::find()->select(['id'])->asArray()->all();
                $model_ids = ArrayHelper::getColumn($model_ids, function ($e) {
                    return (string)$e['_id'];
                });
            } else {
                $model_ids = $model::find()->select(['id'])->column();
            }
            $deleteId = [];
            foreach ($files as $file) {
                if (!in_array($file['pid'], $model_ids)) {
                    $deleteId[] = $file['id'];
                    echo '.';
                }
            }
            File::deleteAll(['id' => $deleteId]);
            echo PHP_EOL;
        }
    }
}
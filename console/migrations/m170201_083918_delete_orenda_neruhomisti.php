<?php

use common\models\Ads;
use common\models\File;
use common\models\Gallery;
use yii\db\Migration;

class m170201_083918_delete_orenda_neruhomisti extends Migration
{
    public function up()
    {
        /** @var Ads[] $ads */
        $ads = Ads::find()->where(['idCategory' => 1432])->all();
        $count = 0;
        $total = count($ads);
        foreach ($ads as $ad){
            //если есть удаляем главную картинку
            if ($ad->image) {
                Yii::$app->files->deleteFile($ad, 'image');
            }
            //удаляем галерею
            Yii::$app->files->deleteFile($ad, 'images');
            $ad->delete();

            $count++;
            if ($count % 100 == 0){
                echo 'Удалено: ' . $count . ' из ' . $total, PHP_EOL;
            }
        }
    }

    public function down()
    {

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

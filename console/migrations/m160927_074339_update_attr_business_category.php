<?php

use common\models\BusinessCategory;
use yii\db\Migration;

class m160927_074339_update_attr_business_category extends Migration
{
    public function up()
    {
        $model = BusinessCategory::findOne(6645);
        $model->sitemap_en = 1;
        $model->save();
    }

    public function down()
    {
        $model = BusinessCategory::findOne(6645);
        $model->sitemap_en = 0;
        $model->save();
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

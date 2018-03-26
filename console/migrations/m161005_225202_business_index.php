<?php

use yii\db\Migration;

class m161005_225202_business_index extends Migration
{
    public function up()
    {
        $this->createIndex('i_business_city_map','business',['idCity','sitemap_en']);
    }

    public function down()
    {
        echo "m161005_225202_business_index cannot be reverted.\n";

        return false;
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

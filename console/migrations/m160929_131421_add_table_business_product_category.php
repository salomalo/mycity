<?php

use yii\db\Migration;

class m160929_131421_add_table_business_product_category extends Migration
{
    public function up()
    {
        $table = 'business_product_category';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'business_category_id' => $this->integer(),
            'product_category_id' => $this->integer(),
        ]);
        $this->createIndex('business_category_id_index', 'business_product_category', 'business_category_id');
        $this->createIndex('product_category_id_index', 'business_product_category', 'product_category_id');
    }

    public function down()
    {
        $table = 'business_product_category';

        $this->dropTable($table);
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

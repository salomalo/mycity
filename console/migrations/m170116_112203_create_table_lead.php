<?php

use yii\db\Migration;

class m170116_112203_create_table_lead extends Migration
{
    public function up()
    {
        $table_upt = 'lead';
        $this->createTable($table_upt, [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'phone' => $this->string(20)->notNull(),
            'description' => $this->string(255)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('lead');
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

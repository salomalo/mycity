<?php

use yii\db\Migration;

class m161226_114947_create_table_user_reg_info extends Migration
{
    public function up()
    {
        $table_upt = 'user_reg_info';
        $this->createTable($table_upt, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'utm_source' => $this->text(),
            'utm_campaing' => $this->text(),
        ]);

        $this->createIndex("{$table_upt}_user_id", $table_upt, 'user_id');
        $this->addForeignKey("{$table_upt}_user_id_fk", $table_upt, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
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

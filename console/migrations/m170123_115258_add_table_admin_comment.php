<?php

use yii\db\Migration;

class m170123_115258_add_table_admin_comment extends Migration
{
    public function up()
    {
        $table_name = 'admin_comment';
        $this->createTable($table_name, [
            'id' => $this->primaryKey(),
            'text' => $this->string(50)->notNull(),
            'admin_id' => $this->integer()->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('NOW()'),
            'type' => $this->integer(),
            'object_id' => $this->string(50),
        ]);

        $this->addForeignKey("{$table_name}_admin_id_fk", $table_name, 'admin_id', 'admin', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('admin_comment');
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

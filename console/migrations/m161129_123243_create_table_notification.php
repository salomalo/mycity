<?php

use yii\db\Migration;

class m161129_123243_create_table_notification extends Migration
{
    public function up()
    {
        $this->createTable('notification', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'type_of_notification' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'link' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),

        ]);
    }

    public function down()
    {
        $this->dropTable('notification');
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

<?php

use yii\db\Migration;

class m161219_120600_change_notification extends Migration
{
    public function up()
    {
        $this->addColumn('notification', 'type_js', 'INT DEFAULT 0');
        $this->renameColumn('notification','type_of_notification','status');

        $this->createIndex('i_notification_type_js','notification','type_js');
        $this->createIndex('i_notification_status','notification','status');
    }

    public function down()
    {
        echo "m161219_120600_change_notification cannot be reverted.\n";

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

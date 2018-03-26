<?php

use yii\db\Migration;

class m161219_120601_change_notification extends Migration
{
    public function up()
    {
        $this->alterColumn('notification', 'title', $this->string(255));
        $this->alterColumn('notification', 'title', 'SET DEFAULT NULL');
        $this->execute('ALTER TABLE notification ALTER COLUMN link DROP NOT NULL');
        $this->execute('ALTER TABLE notification ALTER COLUMN title DROP NOT NULL');
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

<?php

use yii\db\Migration;

class m161205_131557_edit_user_table extends Migration
{
    public function up()
    {
        $this->dropIndex('user_unique_username', 'user');
    }

    public function down()
    {
        $this->createIndex('user_unique_username', '{{%user}}', 'username', true);
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

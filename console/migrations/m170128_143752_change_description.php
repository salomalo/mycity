<?php

use yii\db\Migration;

class m170128_143752_change_description extends Migration
{
    public function up()
    {
        $this->alterColumn('lead', 'description', $this->text());
        $this->alterColumn('admin_comment', 'text', $this->text());
    }

    public function down()
    {
        echo "m170128_143752_change_description cannot be reverted.\n";

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

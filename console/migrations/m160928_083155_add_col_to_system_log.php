<?php

use yii\db\Migration;

class m160928_083155_add_col_to_system_log extends Migration
{
    public function up()
    {
        $table = 'system_log';

        $this->addColumn($table, 'status', $this->string()->notNull()->defaultValue("info"));
    }

    public function down()
    {
        $table = 'system_log';

        $this->dropColumn($table, 'status');
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

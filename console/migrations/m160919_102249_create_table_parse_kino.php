<?php

use yii\db\Migration;

class m160919_102249_create_table_parse_kino extends Migration
{
    public function up()
    {
        $table = 'parse_kino';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'remote_cinema_id' => $this->integer(),
            'local_cinema_id' => $this->integer(),
        ]);
    }

    public function down()
    {
        echo "m160919_102249_create_table_parse_kino cannot be reverted.\n";

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
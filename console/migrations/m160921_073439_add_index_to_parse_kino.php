<?php

use yii\db\Migration;

class m160921_073439_add_index_to_parse_kino extends Migration
{
    public function up()
    {
        $this->createIndex('parse_kino_remote_cinema_index', 'parse_kino', 'remote_cinema_id');
        $this->createIndex('parse_kino_local_cinema_index', 'parse_kino', 'local_cinema_id');
    }

    public function down()
    {
        echo "m160921_073439_add_index_to_parse_kino cannot be reverted.\n";

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

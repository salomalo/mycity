<?php

use yii\db\Migration;

class m160927_080531_add_table_karabas_parser extends Migration
{
    public function up()
    {
        $table = 'parse_karabas';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'remote_business_id' => $this->string(),
            'local_business_id' => $this->integer(),
        ]);
        $this->createIndex('parse_karabas_local_business_index', 'parse_karabas', 'local_business_id');

        //$table = 'business';
        //$this->addColumn($table, 'cinema_id', $this->string());
    }

    public function down()
    {


        $table = 'parse_karabas';

        $this->dropTable($table);
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

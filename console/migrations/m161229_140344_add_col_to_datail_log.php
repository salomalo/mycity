<?php

use yii\db\Migration;

class m161229_140344_add_col_to_datail_log extends Migration
{
    public function up()
    {
        $this->addColumn('detail_log', 'ip', $this->string());
    }

    public function down()
    {

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

<?php

use yii\db\Migration;

class m161206_131722_add_col_to_business extends Migration
{
    public function up()
    {
        $this->addColumn('business', 'trailer', $this->string());
    }

    public function down()
    {
        $this->dropColumn('business', 'trailer');
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

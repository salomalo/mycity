<?php

use yii\db\Migration;

class m161227_081538_change_log_backend extends Migration
{
    public function up()
    {
        $this->addColumn('log_backend', 'user_id', $this->integer());
        $this->addColumn('log_backend', 'admin_id', $this->integer());
        $this->addColumn('log_backend', 'ip', $this->string());
        $this->addColumn('log_backend', 'object_id', $this->string());
        $this->addColumn('log_backend', 'type', $this->integer());
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

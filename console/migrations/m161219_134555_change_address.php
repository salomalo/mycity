<?php

use yii\db\Migration;

class m161219_134555_change_address extends Migration
{
    public function up()
    {
        $this->addColumn('business_address', 'street', $this->string(255)->defaultValue(null));
        $this->addColumn('business_address', 'city', $this->string(255)->defaultValue(null));
        $this->addColumn('business_address', 'country', $this->string(255)->defaultValue(null));
        $this->alterColumn('business_address', 'address', 'DROP NOT NULL');

        $this->renameColumn('business_address', 'address', 'oldAddress');
    }

    public function down()
    {
        $this->dropColumn('business_address', 'street');
        $this->dropColumn('business_address', 'city');
        $this->dropColumn('business_address', 'country');
        //$this->alterColumn('business_address', 'oldAddress', $this->text()->notNull());

        $this->renameColumn('business_address', 'oldAddress', 'address');
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

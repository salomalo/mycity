<?php

use yii\db\Migration;

class m170129_095032_add_template extends Migration
{
    public function up()
    {
        $this->createTable('business_template',[
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'alias' => $this->string(255),
            'img' => $this->string(255),
        ]);
        $this->addColumn('business', 'template_id', $this->integer()->defaultValue(null));
        $this->createIndex('i_business_template','business','template_id');
        $this->addForeignKey('f_business_template','business','template_id','business_template','id','SET NULL','CASCADE');
    }

    public function down()
    {
        echo "m170129_095032_add_template cannot be reverted.\n";

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

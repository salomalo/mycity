<?php

use yii\db\Migration;

class m160921_075339_add_col_to_parse_kino extends Migration
{
    public function up()
    {
        $table = 'afisha';

        $this->addColumn($table, 'isChecked', $this->integer()->notNull()->defaultValue(1));
    }

    public function down()
    {
        $table = 'afisha';

        $this->dropColumn($table, 'isChecked');
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

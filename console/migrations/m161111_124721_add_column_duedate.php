<?php

class m161111_124721_add_column_duedate extends yii\db\Migration
{
    public function up()
    {
        $this->addColumn('business', 'due_date', $this->dateTime());
    }

    public function down()
    {
        echo "m161111_124721_add_column_duedate cannot be reverted.\n";

        return false;
    }
}

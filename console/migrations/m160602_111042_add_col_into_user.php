<?php

class m160602_111042_add_col_into_user extends yii\db\Migration
{
    public function up()
    {
        $this->addColumn('user', 'last_activity', $this->timestamp());
    }

    public function down()
    {
        echo "m160602_111042_add_col_into_user cannot be reverted.\n";

        return false;
    }
}

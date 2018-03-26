<?php

class m160425_105020_rechange_column_type_in_schedule_kino extends yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('schedule_kino', 'times', $this->text());
    }

    public function down()
    {
        echo "m160425_105020_rechange_column_type_in_schedule_kino cannot be reverted.\n";

        return false;
    }
}

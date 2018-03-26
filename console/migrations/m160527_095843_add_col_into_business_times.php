<?php

class m160527_095843_add_col_into_business_times extends yii\db\Migration
{
    public function up()
    {
        $this->addColumn('business_time', 'break_start', $this->time());
        $this->addColumn('business_time', 'break_end', $this->time());

        $this->addColumn('city', 'title_ge', $this->string()->after('title'));
    }

    public function down()
    {
        echo "m160527_095843_add_col_into_business_times cannot be reverted.\n";

        return false;
    }
}

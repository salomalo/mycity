<?php

use yii\db\Migration;

class m160421_105447_afisha_insert_col_repeat extends Migration
{
    public function up()
    {
        $this->addColumn('afisha', 'repeat', $this->integer());
    }

    public function down()
    {
        return true;
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m141113_102838_schedule_start_end extends Migration
{
    public function up()
    {
        $this->addColumn('schedule_kino', 'dateStart', Schema::TYPE_DATE);
        $this->addColumn('schedule_kino', 'dateEnd', Schema::TYPE_DATE);
    }

    public function down()
    {
        echo "m141113_102838_schedule_start_end cannot be reverted.\n";

        return false;
    }
}

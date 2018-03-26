<?php

use yii\db\Migration;

class m160111_154504_ticket_idCity extends Migration
{
    public function up()
    {
        $this->addColumn('ticket', 'idCity', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('ticket', 'idCity');
    }
}

<?php

class m150817_114057_afisha_city extends yii\db\Migration
{
    public function up()
    {
        $this->addColumn('schedule_kino', 'idCity', $this->integer()->notNull());
    }

    public function down()
    {
        echo "m150817_114057_afisha_city cannot be reverted.\n";

        return false;
    }
}

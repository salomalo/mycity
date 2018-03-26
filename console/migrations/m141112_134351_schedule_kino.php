<?php

use yii\db\Schema;
use yii\db\Migration;

class m141112_134351_schedule_kino extends Migration
{
    public function up()
    {
        $this->createTable('schedule_kino', [
            'id'            => $this->primaryKey(),
            'idAfisha'      => $this->integer().' NOT NULL',
            'idCompany'     => $this->integer().' NOT NULL',
            'times'         => $this->string(),
            'price'         => $this->string(),
        ]);
    }

    public function down()
    {
        echo "m141112_134351_schedule_kino cannot be reverted.\n";

        return false;
    }
}

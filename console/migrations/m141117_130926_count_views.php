<?php

use yii\db\Schema;
use yii\db\Migration;

class m141117_130926_count_views extends Migration
{
    public function up()
    {
        $this->createTable('count_views', [
            'id'            => $this->primaryKey(),
            'type'          => $this->integer()->notNull(),
            'pid'           => $this->integer() ,
            'pidMongo'      => $this->string() ,
            'count'         => $this->integer()->notNull(),
            'lastIp'        => $this->string() . '(15) NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m141117_130926_count_views cannot be reverted.\n";

        return false;
    }
}

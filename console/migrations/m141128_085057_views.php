<?php

use yii\db\Schema;
use yii\db\Migration;

class m141128_085057_views extends Migration
{
    public function up()
    {
        $this->dropTable('count_views');
        
        $this->createTable('count_views', [
            'id'            => $this->primaryKey(),
            'type'          => $this->integer()->notNull(),
            'pid'           => $this->integer() ,
            'pidMongo'      => $this->string() ,
            'count'         => $this->integer()->notNull(),
            'countMonth'    => $this->integer() . ' NULL',
            'lastIp'        => $this->string() . '(15) NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m141128_085057_views cannot be reverted.\n";

        return false;
    }
}

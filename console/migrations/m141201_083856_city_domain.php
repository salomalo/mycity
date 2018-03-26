<?php

use yii\db\Schema;
use yii\db\Migration;

class m141201_083856_city_domain extends Migration
{
    public function up()
    {
        $this->createTable('parser_domain',[
            'id'            => $this->primaryKey(),
            'idRegion'      => $this->integer() . '(2) NOT NULL',
            'idCity'        => $this->integer()->notNull(),
            'domain'        => 'VARCHAR(50) NOT NULL',
            'mDomain'       => 'VARCHAR(50) NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m141201_083856_city_domain cannot be reverted.\n";

        return false;
    }
}

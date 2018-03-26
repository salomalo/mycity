<?php

use yii\db\Schema;
use yii\db\Migration;

class m141201_131328_parser_firma_date_create extends Migration
{
    public function up()
    {
        $this->addColumn('logParseBusiness', 'dateCreate','TIMESTAMP DEFAULT NULL');
    }

    public function down()
    {
        echo "m141201_131328_parser_firma_date_create cannot be reverted.\n";

        return false;
    }
}

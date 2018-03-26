<?php

use yii\db\Schema;
use yii\db\Migration;

class m150215_143443_add_shortDescription extends Migration
{
    public function up()
    {
        $this->addColumn('business','shortDescription','text');
        $this->execute("UPDATE business SET \"shortDescription\" = description");
    }

    public function down()
    {
        echo "m150215_143443_add_shortDescription cannot be reverted.\n";

        return false;
    }
}

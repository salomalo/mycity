<?php

use yii\db\Schema;
use yii\db\Migration;

class m141125_085549_post_idCity extends Migration
{
    public function up()
    {
        $this->addColumn('post', 'idCity', $this->integer().' NULL');
    }

    public function down()
    {
        echo "m141125_085549_post_idCity cannot be reverted.\n";

        return false;
    }
}

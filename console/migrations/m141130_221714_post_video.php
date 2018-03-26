<?php

use yii\db\Schema;
use yii\db\Migration;

class m141130_221714_post_video extends Migration
{
    public function up()
    {
        $this->addColumn('post', 'video', $this->string());
    }

    public function down()
    {
        echo "m141130_221714_post_video cannot be reverted.\n";

        return false;
    }
}

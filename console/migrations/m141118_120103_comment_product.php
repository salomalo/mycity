<?php

use yii\db\Schema;
use yii\db\Migration;

class m141118_120103_comment_product extends Migration
{
    public function up()
    {
        $this->addColumn('comment', 'pidMongo', $this->string().'');
    }

    public function down()
    {
        echo "m141118_120103_comment_product cannot be reverted.\n";

        return false;
    }
}

<?php

use yii\db\Migration;

class m161104_182042_change_pid_col_gallery extends Migration
{
    public function up()
    {
        $table = 'gallery';

        $this->execute('ALTER TABLE "gallery" ALTER "pid" TYPE text USING ("pid"::text), ALTER "pid" DROP DEFAULT, ALTER "pid" SET NOT NULL ;');
    }

    public function down()
    {
        echo "m161104_182042_change_pid_col_gallery cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

<?php

use yii\db\Migration;

class m161118_132810_change_model_id_to_string_in_favourite extends Migration
{
    public function up()
    {
        $this->alterColumn('favorite', 'object_id', $this->string());
    }

    public function down()
    {
        echo "m161118_132810_change_model_id_to_string_in_favourite cannot be reverted.\n";

        return false;
    }
}

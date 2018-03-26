<?php

use yii\db\Migration;

class m161101_073154_add_background_column extends Migration
{
    public function up()
    {
        $this->addColumn('business', 'background_image', $this->string());
    }

    public function down()
    {
        echo "m161101_073154_add_background_column cannot be reverted.\n";

        return false;
    }
}

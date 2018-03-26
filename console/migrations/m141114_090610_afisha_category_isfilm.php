<?php

use yii\db\Schema;
use yii\db\Migration;

class m141114_090610_afisha_category_isfilm extends Migration
{
    public function up()
    {
        $this->addColumn('afisha_category', 'isFilm', Schema::TYPE_SMALLINT.' NULL DEFAULT 0');
    }

    public function down()
    {
        echo "m141114_090610_afisha_category_isfilm cannot be reverted.\n";

        return false;
    }
}

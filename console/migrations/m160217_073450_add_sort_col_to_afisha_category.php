<?php

use yii\db\Migration;

class m160217_073450_add_sort_col_to_afisha_category extends Migration
{
    public function up()
    {
        $this->addColumn('afisha_category','order','int');
        $this->createIndex('business_idCity_index','business','idCity');
        $this->addColumn('afisha','order','int');
    }

    public function down()
    {
        return true;
    }
}

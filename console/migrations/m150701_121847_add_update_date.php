<?php

use yii\db\Schema;
use yii\db\Migration;

class m150701_121847_add_update_date extends Migration
{
    public function up()
    {  //'seo_description','seo_keywords'
        $this->addColumn('business_category','update_time','timestamp DEFAULT NULL');

    }

    public function down()
    {
        echo "m150612_092157_add_url_models cannot be reverted.\n";

        return false;
    }
}

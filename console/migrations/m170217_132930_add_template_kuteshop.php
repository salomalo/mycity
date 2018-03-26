<?php

use common\models\BusinessTemplate;
use yii\db\Migration;

class m170217_132930_add_template_kuteshop extends Migration
{
    public function up()
    {
        $template = new BusinessTemplate();
        $template->title = 'kuteshop';
        $template->alias = 'kuteshop';
        $template->save();
    }

    public function down()
    {

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

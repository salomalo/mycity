<?php

use common\models\BusinessTemplate;
use yii\db\Migration;

class m170302_130934_add_template_default extends Migration
{
    public function up()
    {
        $template = new BusinessTemplate();
        $template->title = 'shop';
        $template->alias = 'shop';
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

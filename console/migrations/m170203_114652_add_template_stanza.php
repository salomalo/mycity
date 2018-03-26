<?php

use common\models\BusinessTemplate;
use yii\db\Migration;

class m170203_114652_add_template_stanza extends Migration
{
    public function up()
    {
        $template = new BusinessTemplate();
        $template->title = 'stanza';
        $template->alias = 'stanza';
        $template->save();
    }

    public function down()
    {
        echo "m170203_114652_add_template_stanza cannot be reverted.\n";

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

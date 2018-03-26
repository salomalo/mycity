<?php

use yii\db\Migration;

class m161228_232723_fix_log_backend extends Migration
{
    public function up()
    {
        $this->execute("UPDATE log_backend logs 
                        SET admin_id = p.id 
                        FROM( 
                          SELECT id, username 
                          FROM admin 
                          GROUP BY username,id 
                          ) p 
                        WHERE (logs.username = p.username);");
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

<?php

use yii\db\Schema;
use yii\db\Migration;

class m141008_163757_city extends Migration
{
    public function up()
    {
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
        ]);

        $this->createTable('city', [
            'id' => $this->primaryKey(),
            'idRegion' => $this->integer(2)->notNull(),
            'title' => $this->text(),
            'code' => $this->string(7),
            'subdomain' => $this->string(50),
            'main' => $this->integer(1),
            'image' => $this->string(255),
            'vk_public_id' => $this->integer(),
            'vk_public_admin_id' => $this->integer(),
            'vk_public_admin_token' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('region');
        $this->dropTable('city');
    }
}

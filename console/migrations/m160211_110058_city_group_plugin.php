<?php

use yii\db\Migration;

class m160211_110058_city_group_plugin extends Migration
{
    public function up()
    {
        $this->createTable('widget_city_public', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'group_id' => $this->text()->notNull(),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'network' => $this->text()->notNull(),
        ]);
        $this->createIndex('city_group_plugin_index','widget_city_public','city_id');
    }

    public function down()
    {
        $this->dropTable('widget_city_public');
        
        return true;
    }
}
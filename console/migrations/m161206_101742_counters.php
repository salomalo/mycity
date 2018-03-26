<?php

use yii\db\Migration;

class m161206_101742_counters extends Migration
{
    public function up()
    {
        $table = 'counter';

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'for_office' => $this->integer(1)->defaultValue(0)->notNull(),
            'for_main' => $this->integer(1)->defaultValue(0)->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex("{$table}_for_office_index", $table, ['for_office', 'id']);

        $link_table = 'counter_city';

        $this->createTable($link_table, [
            'id' => $this->primaryKey(),
            'counter_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex("{$link_table}_city_id_index", $link_table, 'city_id');
        $this->createIndex("{$link_table}_counter_id_index", $link_table, ['counter_id', 'city_id']);

        $this->addForeignKey("{$link_table}_city_id_fk", $link_table, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$link_table}_counter_id_fk", $link_table, 'counter_id', $table, 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m161206_101742_counters cannot be reverted.\n";

        return false;
    }
}

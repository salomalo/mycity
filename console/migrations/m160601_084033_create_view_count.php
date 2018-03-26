<?php

class m160601_084033_create_view_count extends yii\db\Migration
{
    private $t = 'view_count';

    public function up()
    {
        $this->createTable($this->t, [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer(),
            'category' => $this->integer(),
            'value' => $this->integer(),
            'year' => $this->integer(4),
            'month' => $this->integer(2),
            'city_id' => $this->integer(),
        ]);

        $this->createIndex('index_for_view_count_with_date', $this->t, ['item_id', 'category', 'year', 'month']);
        $this->createIndex('view_count_index_by_item_and_cat', $this->t, ['item_id', 'category']);
    }

    public function down()
    {
        $this->dropTable($this->t);
    }
}

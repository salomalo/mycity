<?php

class m160830_070650_create_table_admin_city extends yii\db\Migration
{
    private $t = 'admin_city';

    public function up()
    {
        $this->createTable($this->t, [
            'id' => $this->primaryKey(),
            'admin_id' => $this->integer(),
            'city_id' => $this->integer(),
        ]);

        $this->createIndex("{$this->t}_admin_id_index", $this->t, 'admin_id');
        $this->createIndex("{$this->t}_city_id_index", $this->t, 'city_id');

        $this->addForeignKey("{$this->t}_admin_key", $this->t, 'admin_id', 'admin', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$this->t}_city_key", $this->t, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->t);
    }
}

<?php

class m160818_092358_create_advertisement extends yii\db\Migration
{
    private $t = 'advertisement';

    public function up()
    {
        $this->createTable($this->t, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'city_id' => $this->integer(),
            'title' => $this->string(),
            'image' => $this->string(),
            'url' => $this->string(),
            'date_start' => $this->date()->notNull(),
            'date_end' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->createIndex("{$this->t}_user_id_index", $this->t, ['user_id', 'status', 'position']);
        $this->createIndex("{$this->t}_date_index", $this->t, ['date_start', 'date_end', 'status', 'position']);

        $this->addForeignKey("{$this->t}_user_id_foreign_key", $this->t, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->t);
    }
}

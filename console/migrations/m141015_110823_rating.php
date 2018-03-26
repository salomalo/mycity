<?php

class m141015_110823_rating extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('rating', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'pid' => $this->integer()->notNull(),
            'rating' => $this->integer()->notNull(),
        ]);

        $this->createTable('rating_history', [
            'id' => $this->primaryKey(),
            'idUser' => $this->integer()->notNull(),
            'idRating' => $this->integer()->notNull(),
            'ratio' => 'ratio_type NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('rating');
        $this->dropTable('rating_history');
    }
}

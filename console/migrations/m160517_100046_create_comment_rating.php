<?php

class m160517_100046_create_comment_rating extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('comment_rating', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer(),
            'user_id' => $this->integer(),
            'ip' => $this->string(15),
            'vote' => $this->integer(),
            'date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    public function down()
    {
        $this->dropTable('comment_rating');
    }
}

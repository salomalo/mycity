<?php

class m140804_155404_comments extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('comment',[
            'id'            => $this->primaryKey(),
            'idUser'        => $this->integer()->notNull(),
            'text'          => $this->text()->notNull(),
            'type'          => $this->integer()->notNull(),
            'pid'           => $this->integer()->notNull(),
            'parentId'      => $this->integer(),
            'like'          => $this->integer(),
            'unlike'        => $this->integer(),
            'lastIpLike'    => $this->string(15),
            'rating'        => $this->float(),
            'ratingCount'   => $this->integer(),
            'lastIpRating'  => $this->string(15),
            'dateCreate'    => $this->timestamp(),
        ]);
    }

    public function down()
    {
        $this->dropTable('comment');
    }
}

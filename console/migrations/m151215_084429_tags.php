<?php

class m151215_084429_tags extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);
        
        $this->addColumn('afisha', 'tags', $this->text());
        $this->addColumn('action', 'tags', $this->text());
        $this->addColumn('business', 'tags', $this->text());
        $this->addColumn('post', 'tags', $this->text());
    }

    public function down()
    {
        $this->dropTable('tag');
    }
}

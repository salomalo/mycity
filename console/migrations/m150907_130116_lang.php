<?php

class m150907_130116_lang extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%lang}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'local' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'default' => $this->integer(1)->notNull()->defaultValue(0),
            'date_update' => $this->integer()->notNull(),
            'date_create' => $this->integer()->notNull(),
        ]);

        $this->batchInsert('lang', ['url', 'local', 'name', 'default', 'date_update', 'date_create'], [
            ['ru', 'ru-RU', 'Русский', 1, time(), time()],
            ['uk', 'uk-Uk', 'Украинский', 0, time(), time()],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%lang}}');
    }
}

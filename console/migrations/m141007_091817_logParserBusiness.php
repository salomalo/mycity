<?php

class m141007_091817_logParserBusiness extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('logParseBusiness', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'url' => $this->text(),
            'message' => $this->text(),
            'isFail' => $this->integer()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('logParseBusiness');
    }
}

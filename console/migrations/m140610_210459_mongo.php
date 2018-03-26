<?php

class m140610_210459_mongo extends \common\extensions\MongoMigration
{
    public function up()
    {
        $this->execute(['create' => 'product']);
        $this->execute(['create' => 'item']);
        $this->execute(['create' => 'ads']);
    }

    public function down()
    {
        echo "m140610_210459_mongo cannot be reverted.\n";

        return false;
    }
}

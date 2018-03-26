<?php

class m160427_102230_create_afisha_week_repeat extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('afisha_week_repeat', [
            'id' => $this->primaryKey(),
            'afisha_id' => $this->integer(),
            'mon' => $this->boolean(),
            'tue' => $this->boolean(),
            'wed' => $this->boolean(),
            'thu' => $this->boolean(),
            'fri' => $this->boolean(),
            'sat' => $this->boolean(),
            'sun' => $this->boolean(),
        ]);
        $this->createIndex('afisha_id_index', 'afisha_week_repeat', 'afisha_id');
    }

    public function down()
    {
        $this->dropTable('afisha_week_repeat');
    }
}

<?php

class m160608_052553_create_detail_log extends yii\db\Migration
{
    private $t = 'detail_log';

    public function up()
    {
        $this->createTable($this->t, [
            'id'            => $this->primaryKey(),
            'time'          => $this->integer(),
            'subject_id'    => $this->integer(),
            'subject_type'  => $this->integer(),
            'object_id'     => $this->string(),
            'object_type'   => $this->integer(),
            'action'        => $this->integer(),
            'city_id'       => $this->integer(),
        ]);

        $this->createIndex('time_i', $this->t, ['time']);
        $this->createIndex('time_and_action_i', $this->t, ['time', 'action']);
        $this->createIndex('time_and_action_and_obj_type_i', $this->t, ['time', 'action', 'object_type']);
    }

    public function down()
    {
        $this->dropTable($this->t);
    }
}

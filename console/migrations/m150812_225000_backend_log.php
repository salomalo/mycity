<?php

class m150812_225000_backend_log extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('log_backend',[
            'id'            => $this->primaryKey(),
            'username'      => $this->string(),
            'operation'     => $this->string(),
            'description'   => $this->text(),
            'dateCreate'    => $this->timestamp(),
        ]);

        $this->createTable('admin',[
            'id'                    => $this->primaryKey(),
            'username'              => $this->string(),
            'password_hash'         => $this->string(),
            'level'                 => $this->integer(),
            'auth_key'              => $this->string(),
            'access_token'          => $this->string(),
            'password_reset_token'  => $this->string(),
            'dateCreate'            => $this->timestamp(),
        ]);
    }

    public function down()
    {
        echo "m150812_225000_backend_log cannot be reverted.\n";

        return false;
    }
}

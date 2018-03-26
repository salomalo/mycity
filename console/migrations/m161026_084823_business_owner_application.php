<?php
use yii\db\Migration;

class m161026_084823_business_owner_application extends Migration
{
    public function up()
    {
        $table = 'business_owner_application';

        $this->createTable('business_owner_application', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'business_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'token' => $this->string(256),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->createIndex("{$table}_user_id_index", $table, ['user_id', 'status']);
        $this->createIndex("{$table}_business_id_index", $table, ['business_id', 'status']);
        $this->createIndex("{$table}_token_index", $table, ['token']);

        $this->addForeignKey("{$table}_user_id_foreign_key", $table, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$table}_business_id_foreign_key", $table, 'business_id', 'business', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m160823_111125_create_business_owner_application cannot be reverted.\n";

        return false;
    }
}

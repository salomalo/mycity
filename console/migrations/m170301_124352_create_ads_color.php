<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ads_color`.
 */
class m170301_124352_create_ads_color extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ads_color', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'isShowOnBusiness' => $this->integer(),
            'image' => $this->string(),
            'idAds' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ads_color');
    }
}

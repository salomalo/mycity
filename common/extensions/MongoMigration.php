<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 6/10/14
 * Time: 11:57 PM
 */

namespace common\extensions;

use yii\mongodb\Database;

class MongoMigration extends \yii\base\Component
{

    /**@var Database $db*/
    public $db;

    /**
     * Initializes the migration.
     * This method will set [[db]] to be the 'db' application component, if it is null.
     */
    public function init()
    {
        parent::init();
        $connection = \Yii::$app->mongodb;
        $this->db = $connection->getDatabase();
    }

    /**
     * This method contains the logic to be executed when applying this migration.
     * Child classes may override this method to provide actual migration logic.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function up()
    {

    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * The default implementation throws an exception indicating the migration cannot be removed.
     * Child classes may override this method if the corresponding migrations can be removed.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function down()
    {

    }

    public function execute($command, $options = [])
    {
        echo "    > execute: " . json_encode($command) ."...";
        $time = microtime(true);
        $this->db->executeCommand($command, $options);
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }

}

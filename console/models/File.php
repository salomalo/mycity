<?php
namespace console\models;

use common\models\Product as ProductDB;
use yii;
use common\models\File as FileDB;

class File extends FileDB
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function copyFromParser()
    {
        $db2FilesQuery = self::find()->where(['type' => FileDB::TYPE_PRODUCT]);

        /** @var self[] $db2Files */
        foreach ($db2FilesQuery->batch() as $db2Files) {
            foreach ($db2Files as $db2File) {
                if (FileDB::find()->where(['name' => $db2File->name])->count()) {
                    /** @var ProductDB $dbProduct */
                    $dbProduct = ProductDB::find()->where(['image' => $db2File->name])->one();
                    if ($dbProduct) {
                        $dbFile = new FileDB();
                        $dbFile->attributes = $db2File->attributes;
                        $dbFile->pidMongo = (string)$dbProduct->_id;
                        $dbFile->save();
                    }
                }
            }
        }
    }
}

<?php

namespace console\controllers;

use yii;
use yii\console\Controller;

/**
 * Description of CronController
 *
 * @author dima
 */
class CronController extends Controller
{
    public $file = '@app/../yii';
    
    public function actionIndex()
    {
        $controllers = ['sitemap-cities/index'];
        foreach ($controllers as $item) {
            $this->run($item);
        }
    }
    
    public function run($cmd)
    {
        $cmd = PHP_BINDIR . '/php ' . ' ' .Yii::getAlias($this->file) . ' ' . $cmd;
        pclose(popen($cmd . ' > /dev/null &', 'r'));
        return true;
    }
}

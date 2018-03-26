<?php

/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.11.2016
 * Time: 13:34
 */

namespace office\extensions\BlockBusinessQuestion;

use common\models\File;
use common\models\QuestionConversation;
use common\models\Ticket;
use Yii;

class BlockBusinessQuestion extends \yii\base\Widget
{
    public $business;

    public function run()
    {
        if (Yii::$app->user->identity->id) {
            if (isset($this->business) && $this->business) {
                $countQuestion = QuestionConversation::find()
                    ->where(['object_type' => File::TYPE_BUSINESS, 'object_id' => $this->business->id])
                    ->count();
            } else {
                $countQuestion = 0;
            }
        } else {
            $countQuestion = 0;
        }

        return $this->render('index', [
            'countQuestion' => $countQuestion,
            'businessId' => $this->business->id,
        ]);
    }
}
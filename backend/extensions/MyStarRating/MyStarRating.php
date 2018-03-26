<?php
namespace backend\extensions\MyStarRating;

use kartik\rating\StarRating;
use yii;
use yii\base\Widget;

class MyStarRating extends Widget
{
    public $rating;

    public function run()
    {
        $this->view->registerJs(@file_get_contents('../extensions/MyStarRating/event.js'));
        return StarRating::widget([
            'name' => 'rating',
            'pluginOptions' => [
                'showClear' => false,
                'size' => 'sm',
                'step' => 1,
                'readonly' => true,
                'showCaption' => true,
                'animate' => false,
            ],
            'value' => $this->rating,
        ]);
    }
}
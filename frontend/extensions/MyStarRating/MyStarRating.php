<?php

namespace frontend\extensions\MyStarRating;

use kartik\rating\StarRating;
use yii;
use yii\base\Widget;
use yii\helpers\Url;

class MyStarRating extends Widget
{
    public $id;
    public $rating;
    public $url = '';
    public $readOnly = false;

    public function init()
    {
        $this->url = Url::to($this->url);
    }

    public function run()
    {
        return StarRating::widget([
            'name' => 'rating',
            'pluginOptions' => [
                'theme' => 'krajee-fa',
                'filledStar' => '<i data-alt="1" class="fa fa-star" title="gorgeous"></i>',
                'emptyStar' => '<i data-alt="1" class="fa fa-star-o" title="Not rated yet!"></i>',
                'showClear' => false,
                'size' => 'xs',
                'step' => 1,
                'showCaption' => false,
                'animate' => false,
                'readonly' => $this->readOnly,
            ],
            'pluginEvents' => [
                'rating.change' => "function (event, value, caption) {
                    event.preventDefault();
                    var rating = $(this);
                    $.ajax('{$this->url}', {
                        method: 'post',
                        dataType: 'json',
                        data: {id: $(this).attr('id'), value: value},
                        complete: function(response) {
                            var val = $.parseJSON(response.responseText);
                            if (response.status === 403) {
                                signup('/ru/user/security/login-ajax');
                                rating.rating('reset');
                                return false;
                            }
                            rating.rating('update', val.rating);
                        }
                    });
                }",
            ],
            'id' => $this->id,
            'value' => $this->rating,
        ]);
    }
}

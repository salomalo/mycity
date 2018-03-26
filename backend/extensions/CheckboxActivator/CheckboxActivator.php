<?php

namespace backend\extensions\CheckboxActivator;

use yii\base\Widget;

/**
 * Description of CheckboxActivator
 *
 * @author bogdan
 */
class CheckboxActivator extends Widget
{
//    public $publishOptions = ['forceCopy' => true];
    
    /** @var string $element */
    public $element;

    /** @var bool $isSet */
    public $isSet;

    public $titles = [
        'times2D' => 'Сеансы в 2D',
        'times3D' => 'Сеансы в 3D',
    ];

    public function run()
    {
        return $this->render('main', [
            'element' => $this->element,
            'title' => $this->titles[$this->element],
            'class' => $this->isSet ? 'btn-success' : 'btn-default'
        ]);
    }

    public function init()
    {
        $this->getView()->registerJsFile('/js/CheckboxActivator.js', ['depends' => 'yii\web\JqueryAsset']);
        parent::init();
    }
}

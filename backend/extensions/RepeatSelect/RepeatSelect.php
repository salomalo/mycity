<?php

namespace backend\extensions\RepeatSelect;

use common\models\Afisha;
use yii\base\Widget;
use yii\widgets\ActiveForm;

/**
 * Description of RepeatSelect
 *
 * @author bogdan
 */
class RepeatSelect extends Widget
{
//    public $publishOptions = ['forceCopy' => true];

    /** @var $form ActiveForm */
    public $form;

    /** @var $model Afisha */
    public $model;

    public function run()
    {
        return $this->render('main', [
            'form' => $this->form,
            'model' => $this->model,
        ]);
    }

    public function init()
    {
        $this->getView()->registerJsFile('/js/RepeatSelect.js', ['depends' => 'yii\web\JqueryAsset']);
        parent::init();
    }
}

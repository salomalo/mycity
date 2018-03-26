<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 5/4/14
 * Time: 7:45 PM
 */
namespace backend\extensions\SelectUser;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\widgets\InputWidget;

class SelectUser extends InputWidget
{
    public $pluginOptions = [];


    public function init()
    {
        parent::init();
        $url = Yii::$app->urlManager->createUrl('index');
        $this->renderInput();

        // Script to initialize the selection based on the value of the select2 element
        $initScript = <<<SCRIPT
        function (element, callback) {
            var id=$(element).val();
            if (id !== "") {
                $.ajax("{$url}?id=" + id, {
                    dataType: "json"
                }).done(function(data) { callback(data.results);});
            }
        }
SCRIPT;

        // The widget
//        Html::activeInput('text',$this->model, $this->attribute)->widget(Select2::classname(), [
//            'options' => ['placeholder' => 'Search for a city ...'],
//            'pluginOptions' => [
//                'allowClear' => true,
//                'ajax' => [
//                    'url' => $url,
//                    'dataType' => 'json',
//                    'data' => new JsExpression('function(term,page) { return {search:term}; }'),
//                    'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
//                ],
//                'initSelection' => new JsExpression($initScript)
//            ],
//        ]);

//        $this->registerAssets();

    }

    /**
     * Embeds the input group addon
     */
    protected function embedAddon($input)
    {
        if (!empty($this->addon)) {
            $addon = $this->addon;
            $prepend = ArrayHelper::getValue($addon, 'prepend', '');
            $append = ArrayHelper::getValue($addon, 'append', '');
            $group = ArrayHelper::getValue($addon, 'groupOptions', []);
            $size = isset($this->size) ? ' input-group-' . $this->size : '';
            if (is_array($prepend)) {
                $content = ArrayHelper::getValue($prepend, 'content', '');
                if (isset($prepend['asButton']) && $prepend['asButton'] == true) {
                    $prepend = Html::tag('div', $content, ['class' => 'input-group-btn']);
                } else {
                    $prepend = Html::tag('span', $content, ['class' => 'input-group-addon']);
                }
                Html::addCssClass($group, 'input-group' . $size . ' select2-bootstrap-prepend');
            }
            if (is_array($append)) {
                $content = ArrayHelper::getValue($append, 'content', '');
                if (isset($append['asButton']) && $append['asButton'] == true) {
                    $append = Html::tag('div', $content, ['class' => 'input-group-btn']);
                } else {
                    $append = Html::tag('span', $content, ['class' => 'input-group-addon']);
                }
                Html::addCssClass($group, 'input-group' . $size . ' select2-bootstrap-append');
            }
            $addonText = $prepend . $input . $append;
            $contentBefore = ArrayHelper::getValue($addon, 'contentBefore', '');
            $contentAfter = ArrayHelper::getValue($addon, 'contentAfter', '');
            return Html::tag('div', $contentBefore . $addonText . $contentAfter, $group);
        }
        return $input;
    }

    /**
     * Renders the source Input for the Select2 plugin.
     * Graceful fallback to a normal HTML select dropdown
     * or text input - in case JQuery is not supported by
     * the browser
     */
    protected function renderInput()
    {
        $input = Html::activeInput('text', $this->model, $this->attribute);
        $id = Html::getInputId($this->model, $this->attribute);
        echo $input;
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        if (!empty($this->language) && $this->language != 'en' && $this->language != 'en_US') {
            Select2Asset::register($view)->js[] = 'select2_locale_' . $this->language . '.js';
        } else {
            Select2Asset::register($view);
        }
        $this->pluginOptions['width'] = 'resolve';
        $this->registerPlugin('select2');
    }
}
<?php
namespace common\extensions\BusinessCustomField;

use common\models\Business;
use common\models\BusinessCategory;
use common\models\BusinessCustomField;
use kartik\widgets\Select2;
use yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CustomFieldFilter extends Widget
{
    /** @var null|integer $category */
    public $category;

    /** @var null|array $attributes */
    public $attributes = null;

    public function init()
    {
        parent::init();

        CustomFieldFilterAssets::register($this->view);
    }

    public function run()
    {
        $custom_fields = [];
        if ($this->category and $categories = BusinessCategory::find()->where(['id' => $this->category])->one()) {
            /** @var $categories BusinessCategory */
            $categories = $categories->parents()->select('id')->column();
            $categories[] = $this->category;

            $custom_fields = BusinessCustomField::find()->joinWith('businessCategoryLinks')
                ->where(['business_category_custom_field_link.business_category_id' => $categories])
                ->all();
        }

        return $this->render('filter/main', ['custom_fields' => $custom_fields, 'attributes' => $this->attributes]);
    }
    
    public function getSwitchInput()
    {
        return function (BusinessCustomField $custom_field, $value) {
            switch ($custom_field->filter_type) {
                case BusinessCustomField::FILTER_SELECT:
                    $value = (int)$value;
                    $data = $custom_field->hasDefault ? $custom_field->defaultValuesForForm : $custom_field->allValuesForForm;
                    $field = Select2::widget([
                        'data' => $data,
                        'value' => $value,
                        'name' => "attr[{$custom_field->id}]",
                        'hideSearch' => true,
                        'options' => ['multiple' => false, 'placeholder' => 'Выберите значение'],
                        'pluginOptions' => ['allowClear' => true],
                    ]);
                    break;

                case BusinessCustomField::FILTER_SINGLE_INPUT:
                    $field = Html::input('text', "attr[{$custom_field->id}]", $value, [
                        'class' => 'form-control input-sm', 'placeholder' => Yii::t('business', 'Value')
                    ]);
                    break;

                case BusinessCustomField::FILTER_DOUBLE_INPUT:
                    $from = ArrayHelper::getValue($value, 'from');
                    $to = ArrayHelper::getValue($value, 'to');
                    $field[] = Html::beginTag('div', ['class' => 'row dual-input']);

                    $field[] = Html::input('text', "attr[$custom_field->id][from]", $from, [
                        'class' => 'form-control col-sm-5 input-sm', 'placeholder' => Yii::t('business', 'From'),
                    ]);
                    $field[] = Html::input('text', "attr[$custom_field->id][to]", $to, [
                        'class' => 'form-control col-sm-5 input-sm', 'placeholder' => Yii::t('business', 'To'),
                    ]);

                    $field[] = Html::endTag('div');
                    $field = implode($field);
                    break;

                case BusinessCustomField::FILTER_CHECKBOXES:
                    $data = $custom_field->hasDefault ? $custom_field->defaultValuesForForm : $custom_field->allValuesForForm;
                    $field = Html::checkboxList("attr[$custom_field->id]", $value, $data, [
                        'unselect' => '',
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $return[] = Html::beginTag('div', ['class' => 'checkbox']);
                            $return[] = Html::beginTag('label');
                            $return[] = Html::checkbox($name, $checked, ['value' => $value]);
                            $return[] = Html::beginTag('span');
                            $return[] = $label;
                            $return[] = Html::endTag('span');
                            $return[] = Html::endTag('label');
                            $return[] = Html::endTag('div');

                            return implode(PHP_EOL, $return);
                        },
                    ]);
                    break;

                default:
                    $field = null;
            }
            return $field;
        };
    }
}
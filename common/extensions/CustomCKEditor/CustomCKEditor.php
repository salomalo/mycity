<?php

namespace common\extensions\CustomCKEditor;


use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;

class CustomCKEditor extends CKEditor
{
    public function init()
    {
        parent::init();
        $this->presetCustom();
    }

    private function presetCustom(){
        $options['height'] = 400;

        $options['fontSize_defaultLabel'] = '14px';
        $options['font_defaultLabel'] = 'Arial';

        $options['toolbar'] = [
            [
                'name' => 'row1',
                'items' => [
                    'Bold', 'Italic', 'Underline', 'Strike', '-',
                    'Subscript', 'Superscript', 'RemoveFormat', '-',
                    'NumberedList', 'BulletedList', '-',
                    'Outdent', 'Indent', '-',
                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                    'BidiLtr', 'BidiRtl', '-',
                    '/',
                    'Link', 'Unlink', 'Anchor', '-',

                ],
            ],
            [
                'name' => 'row2',
                'items' => [
                    'Styles', 'Format', 'Font', 'FontSize',
                    'TextColor', 'BGColor', '-',
                    'ShowBlocks', 'Maximize', '-',
                    'Image', 'Table', '-',
                    'About',
                ],
            ],
        ];

        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);
    }
}
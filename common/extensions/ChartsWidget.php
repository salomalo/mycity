<?php

namespace common\extensions;

use InvalidArgumentException;
use miloschuman\highcharts\Highcharts;
use yii\base\Widget;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Class ChartsWidget
 * @package terminal\extensions
 */
class ChartsWidget extends Widget
{
    /** @var $data array */
    public $data;

    /** @var $type integer */
    public $type = 0;

    /** @var $config array */
    private $config;

    /** Тип графика для указания в конфиге при вызове виджета */
    const TYPE_PIE                          = 0;
    const TYPE_TIME_SERIES                  = 1;
    const TYPE_CHART_WITH_DATA_LABELS       = 2;
    const TYPE_STACKED_AND_GROUPED_CHART    = 3;
    const TYPE_CHART_WITH_BASIC_COL         = 4;
    const TYPE_CHART_BASIC_AREA             = 5;
    const TYPE_CHART_STACKED_AREA           = 6;
    const TYPE_CHART_STACKED_COLUMN         = 7;

    public $yAxis;
    public $title;
    public $subTitle;

    /**
     * Регистрируем js для ссылки по клику на график
     */
    public function init()
    {
        $view = $this->getView();
        /**
         * 1. Ховер для курсора 2. В случае клика, а не выделения получаем ссылку из data-url и переходим по ней
         */
        $js = ';var chart = $(".widget-chart-js");
                chart.hover(function(){$(this).css("cursor","pointer");},function(){$(this).css("cursor","default");});
                chart.mousedown(function(e){this.mouseX = e.pageX;this.mouseY = e.pageY;})
                .mouseup(function(e){if((this.mouseX === e.pageX) && (this.mouseY === e.pageY)){window.location.href = $(this).data("url");}});';
        $view->registerJs($js, View::POS_READY);
        parent::init();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $this->makeConfig();

        return Highcharts::widget($this->config);
    }

    /**
     * Создание конфига для графика
     */
    private function makeConfig()
    {
        switch ($this->type) {
            case self::TYPE_PIE:
                $this->data = $this->getPieData();
                $this->config = $this->getPieConfig();
                break;
            case self::TYPE_TIME_SERIES:
                $this->data = $this->getTimeSeriesData();
                $this->config = $this->getTimeSeriesConfig();
                break;
            case self::TYPE_CHART_WITH_DATA_LABELS:
                $this->data = $this->getCharWithLabelsData();
                $this->config = $this->getCharWithLabelsConfig();
                break;
            case self::TYPE_STACKED_AND_GROUPED_CHART:
                $this->data = $this->getStackedAndGroupedCharData();
                $this->config = $this->getStackedAndGroupedCharConfig();
                break;
            case self::TYPE_CHART_WITH_BASIC_COL:
                $this->data = $this->getChartWithBasicColumnData();
                $this->config = $this->getChartWithBasicColumnConfig();
                break;
            case self::TYPE_CHART_BASIC_AREA:
                $this->data = $this->getChartWithBasicAreaData();
                $this->config = $this->getChartWithBasicAreaConfig();
                break;
            case self::TYPE_CHART_STACKED_AREA:
                $this->data = $this->getChartWithStackedAreaData();
                $this->config = $this->getChartWithStackedAreaConfig();
                break;
            case self::TYPE_CHART_STACKED_COLUMN:
                $this->data = $this->getChartWithStackedColumnData();
                $this->config = $this->getChartWithStackedColumnConfig();
                break;
            default:
                throw new InvalidArgumentException('Invalid chart type');
        }
    }

    /**
     * @link http://www.highcharts.com/demo/pie-basic
     * @return array
     */
    private function getPieData()
    {
        $data = [];
        foreach ($this->data as $label => $value) {
            $data[] = [
                'name' => $label, 'y' => $value,
            ];
        }

        return $data;
    }

    /**
     * @link http://www.highcharts.com/demo/pie-basic
     * @return array
     */
    private function getPieConfig()
    {
        return [
            'options' => [
                'credits'     => ['enabled' => false], 'chart' => [
                    'plotBackgroundColor' => null, 'plotBorderWidth' => null, 'plotShadow' => false, 'type' => 'pie',
                ], 'title'    => ['text' => false],
                'tooltip'     => ['pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'],
                'plotOptions' => ['pie' => ['allowPointSelect' => false, 'dataLabels' => ['enabled' => false]]],
                'series'      => [
                    [
                        'name' => 'В процентах', 'colorByPoint' => true, 'data' => $this->data,
                    ]
                ],
            ]
        ];
    }

    /**
     * @link http://www.highcharts.com/demo/line-time-series
     * @return array
     */
    private function getTimeSeriesData()
    {
        $data = [];
        foreach ($this->data as $time => $value) {
            $data[] = [$time, $value];
        }

        return $data;
    }

    /**
     * @link http://www.highcharts.com/demo/line-time-series
     * @return array
     */
    private function getTimeSeriesConfig()
    {
        $color0 = new JsExpression('Highcharts.getOptions().colors[0]');
        $color1 = new JsExpression('Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get(\'rgba\')');

        return [
            'options' => [
                'credits'   => ['enabled' => false],
                'title' => ['text' => $this->title],
                'legend' => ['enabled' => false],
                'xAxis'     => ['type' => 'category', 'labels' => ['enabled' => true]],
                'yAxis'     => ['min' => 0, 'title' => ['text' => $this->yAxis], 'allowDecimals' => false],
                'plotOptions' => [
                    'area' => [
                        'animation' => ['duration' => 3000],
                        'cursor' => 'pointer',
                        'threshold' => null, 'lineWidth' => 1, 'states' => ['hover' => ['lineWidth' => 1]],
                        'marker'    => ['radius' => 2], 'fillColor' => [
                            'linearGradient' => ['x1' => 0, 'y1' => 0, 'x2' => 0, 'y2' => 1],
                            'stops'          => [[0, $color0], [1, $color1]],
                        ],
                    ],
                ], 'series' => [['name' => 'Показатель', 'type' => 'area', 'data' => $this->data]],
            ]
        ];
    }

    /**
     * @link http://www.highcharts.com/demo/line-labels
     * @return array
     */
    private function getCharWithLabelsData()
    {
        $data = [];
        foreach ($this->data as $title => $arr) {
            $ser = [];
            foreach ($arr as $label => $item) {
                $ser[] = [$label, $item];
            }
            $data[] = ['name' => $title, 'data' => $ser];
        }

        return $data;
    }

    /**
     * @link http://www.highcharts.com/demo/line-labels
     * @return array
     */
    private function getCharWithLabelsConfig()
    {
        return [
            'options' => [
                'chart'       => ['type' => 'line'], 'title' => ['text' => $this->title],
                'subtitle'    => ['text' => $this->subTitle], 'xAxis' => ['type' => 'category'],
                'yAxis'       => ['title' => ['text' => $this->yAxis]],
                'plotOptions' => ['line' => ['dataLabels' => ['enabled' => true], 'enableMouseTracking' => false]],
                'series'      => $this->data, 'credits' => ['enabled' => false],
            ]
        ];
    }

    /**
     * @link http://www.highcharts.com/demo/column-stacked-and-grouped
     * @return array
     */
    private function getStackedAndGroupedCharData()
    {
        $return = [];
        $count = count($this->data);
        $i = 1;
        foreach ($this->data as $name => $values) {
            $data = [];
            foreach ($values as $cat => $val) {
                $data[] = [$cat, $val];
            }
            //Первая половина элементов - 1 группа(выданные), вторая - 2 группа(возвращенные)
            $return[] = ['name' => $name, 'data' => $data, 'stack' => ((($i * 2) <= $count) ? 'out' : 'in')];
            $i++;
        }

        return $return;
    }

    /**
     * @link http://www.highcharts.com/demo/column-stacked-and-grouped
     * @return array
     */
    private function getStackedAndGroupedCharConfig()
    {
        $format = 'this.series.name+": "+this.y+" у.е.<br/>Всего: "+this.point.stackTotal+" у.е."';
        $func = new JsExpression("function(){return {$format};}");

        return [
            'options' => [
                'chart'    => ['type' => 'column'], 'title' => ['text' => $this->title],
                'subtitle' => ['text' => $this->subTitle], 'xAxis' => ['type' => 'category'],
                'yAxis'    => ['allowDecimals' => false, 'min' => 0, 'title' => ['text' => $this->yAxis]],
                'tooltip'  => ['formatter' => $func], 'plotOptions' => ['column' => ['stacking' => 'normal']],
                'series'   => $this->data, 'credits' => ['enabled' => false],
            ]
        ];
    }

    /**
     * @link http://www.highcharts.com/demo/column-basic
     * @return array
     */
    private function getChartWithBasicColumnData()
    {
        $return = [];
        foreach ($this->data as $title => $arr) {
            $ser = [];
            foreach ($arr as $label => $item) {
                $ser[] = [$label, $item];
            }
            $return[] = ['name' => $title, 'data' => $ser];
        }

        return $return;
    }

    /**
     * @link http://www.highcharts.com/demo/column-basic
     * @return array
     */
    private function getChartWithBasicColumnConfig()
    {
        $format = '<span style="font-size:10px">{point.key}</span><table>';
        $pointFormat =
            '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.0f} шт</b></td></tr>';

        return [
            'options' => [
                'chart'             => ['type' => 'column'], 'title' => ['text' => $this->title],
                'subtitle'          => ['text' => $this->subTitle], 'xAxis' => ['type' => 'category', 'crosshair' => true],
                'yAxis'             => ['min' => 0, 'title' => ['text' => $this->yAxis]],
                'tooltip'           => ['headerFormat' => $format, 'pointFormat' => $pointFormat, 'footerFormat' => '</table>', 'shared' => true, 'useHTML' => true],
                'plotOptions'       => ['column' => ['pointPadding' => 0.2, 'borderWidth' => 0]],
                'series'            => $this->data,
                'credits'           => ['enabled' => false],
            ]
        ];
    }

    /**
     * http://www.highcharts.com/demo/area-basic
     * @return array
     */
    private function getChartWithBasicAreaData()
    {
        $return = [];
        $series = count($this->data['titles']);
        for ($i = 0; $i < $series; $i++) {
            $seria = [];
            foreach ($this->data['values'] as $label => $values) {
                $seria[] = [(string)$label, $values[$i]];
            }
            $return[] = ['name' => $this->data['titles'][$i], 'data' => $seria];
        }

        return $return;
    }

    /**
     * http://www.highcharts.com/demo/area-basic
     * @return array
     */
    private function getChartWithBasicAreaConfig()
    {
        $format = '{series.name} пользователей: <b>{point.y}</b>';

        return [
            'options' => [
                'chart'     => ['type' => 'area'], 'title' => ['text' => $this->title],
                'subtitle'  => ['text' => $this->subTitle], 'xAxis' => ['type' => 'category', 'crosshair' => true],
                'yAxis'     => ['min' => 0, 'title' => ['text' => $this->yAxis], 'allowDecimals' => false],
                'tooltip'   => ['pointFormat' => $format],
                'plotOptions' => [
                    'area' => [
                        'cursor' => 'pointer',
                        'animation' => ['duration' => 3000],
                        'marker' => [
                            'enabled' => false, 'symbol' => 'circle', 'radius' => 2,
                            'states'  => ['hover' => ['enabled' => true]]
                        ],
                        'fillOpacity' => 0.3,
                    ],
                ],
                'series' => $this->data,
                'credits' => ['enabled' => false],
            ]
        ];
    }

    /**
     * http://www.highcharts.com/demo/area-stacked
     * @return array
     */
    private function getChartWithStackedAreaData()
    {
        $return = [];
        $series = count($this->data['titles']);
        for ($i = 0; $i < $series; $i++) {
            $seria = [];
            foreach ($this->data['values'] as $label => $values) {
                $seria[] = [(string)$label, $values[$i]];
            }
            $return[] = ['name' => $this->data['titles'][$i], 'data' => $seria];
        }

        return $return;
    }

    /**
     * http://www.highcharts.com/demo/area-stacked
     * @return array
     */
    private function getChartWithStackedAreaConfig()
    {
        $formatter = new JsExpression('function () {return this.value;}');

        return [
            'options' => [
                'chart'      => ['type' => 'area'], 'title' => ['text' => $this->title],
                'subtitle'   => ['text' => $this->subTitle],
                'xAxis'      => ['type' => 'category', 'title' => ['enabled' => false], 'tickmarkPlacement' => 'on'],
                'yAxis'      => [
                    'min'  => 0,
                    'title' => ['text' => $this->yAxis],
                    'labels' => ['formatter' => $formatter],
                    'allowDecimals' => false
                ],
                'tooltip' => ['shared' => true],
                'plotOptions' => [
                    'area' => [
                        'animation' => ['duration' => 3000],
                        'cursor' => 'pointer',
                        'stacking' => 'normal', 'lineColor' => '#666666', 'lineWidth' => 1, 'marker' => [
                            'lineWidth' => 1, 'lineColor' => '#666666',
                        ]
                    ],
                ], 'series'  => $this->data, 'credits' => ['enabled' => false],
            ]
        ];
    }

    /**
     * http://www.highcharts.com/demo/column-stacked
     * @return array
     */
    private function getChartWithStackedColumnData()
    {
        $return = [];
        $series = count($this->data['titles']);
        for ($i = 0; $i < $series; $i++) {
            $seria = [];
            foreach ($this->data['values'] as $label => $values) {
                $seria[] = [(string)$label, $values[$i]];
            }
            $return[] = ['name' => $this->data['titles'][$i], 'data' => $seria];
        }

        return $return;
    }

    /**
     * http://www.highcharts.com/demo/column-stacked
     * @return array
     */
    private function getChartWithStackedColumnConfig()
    {
        $formatPoint = '{series.name}: {point.y}<br/>Всего: {point.stackTotal}';
        $colorPlot = new JsExpression('(Highcharts.theme && Highcharts.theme.dataLabelsColor) || \'white\'');
        $colorStack = new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || \'gray\'');
        $colorBack = new JsExpression('(Highcharts.theme && Highcharts.theme.background2) || \'white\'');

        return [
            'options' => [
                'chart'         => ['type' => 'column'], 'title' => ['text' => $this->title],
                'xAxis'         => ['type' => 'category', 'title' => ['enabled' => false]],
                'yAxis'         => ['min' => 0, 'title' => ['text' => $this->yAxis], 'allowDecimals' => false],
                'stackLabels'   => ['enabled' => true, 'style' => ['fontWeight' => 'bold', 'color' => $colorStack]],
                'tooltip'       => ['pointFormat' => $formatPoint],
                'plotOptions'   => ['column' => ['stacking' => 'normal', 'dataLabels' => ['enabled' => true, 'color' => $colorPlot, 'style' => ['textShadow' => '0 0 3px black']]]],
                'series'        => $this->data,
                'credits'       => ['enabled' => false],
            ]
        ];
    }
}

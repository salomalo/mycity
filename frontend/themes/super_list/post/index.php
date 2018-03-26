<?php
/**
 * @var $title string
 * @var $this View
 */

use frontend\extensions\AdBlock;
use frontend\extensions\LastNews\LastNews;
use yii\helpers\ArrayHelper;
use yii\web\View;

$this->title = $title;
$subDomain = ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAIN_TITLE_GE', '');
$subDomainId = ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINID', null);
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <?= LastNews::widget([
                    'title' => empty($subDomain) ? Yii::t('post', 'Last_post_{cityTitle}_all_category', ['cityTitle' => ''])
                        : Yii::t('post', 'Last_post_{cityTitle}_all_category', ['cityTitle' => $subDomain]),
                    'city' => $subDomain,
                    'idCity' => $subDomainId,
                ])?>
            </div>
        </div>
    </div>
</div>
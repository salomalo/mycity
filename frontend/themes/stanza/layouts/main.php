<?php
/**
 * @var $content
 * @var \yii\web\View $this
 */

use frontend\extensions\StanzaLatestBlog\StanzaLatestBlog;
use yii\helpers\Url;

\frontend\themes\stanza\AppAssets::register($this);

$model = $this->context->businessModel;
?>

<div id="wrapper">

    <?= $this->render('main/header', ['model' => $model]) ?>

    <?= $content?>

<!--    --><?php
//    echo $this->render('main/footer', ['model' => $model]);
//    ?>

    <a href="#" class="scroll_top"><i class="fa fa-chevron-up fa-2x"></i></a><!-- ( SCROLL TOP END ) -->

</div><!-- ( WRAPPER END ) -->
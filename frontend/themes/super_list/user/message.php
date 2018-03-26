<?php
/**
 * @var yii\web\View            $this
 * @var dektrium\user\Module    $module
 */

$this->title = $title;
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <?= $this->render('/_alert', ['module' => $module]) ?>
        </div>
    </div>
</div>
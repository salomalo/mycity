<?php
/**
 * @var $this View
 * @var $cf array
 */
use yii\web\View;
?>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tbody>

        <?php foreach ($cf as $title => $value) : ?>
            <?= $this->render('field', ['title' => $title, 'value' => $value]) ?>
        <?php endforeach; ?>
    
    </tbody>
</table>
<?php
/**
 * @var dektrium\user\Module $module
 */
?>

<?php if ($module->enableFlashMessages and ($flash = Yii::$app->session->getAllFlashes())): ?>
    <div class="row">
        <div class="col-xs-12">
            <?php foreach ($flash as $type => $message): ?>
                <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                    <div class="alert alert-<?= $type ?>">
                        <?= $message ?>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
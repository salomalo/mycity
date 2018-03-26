<?php
/**
 * @var $this \yii\web\View
 * @var $categories array
 * @var $selected integer
 */
?>

<div>
    <label for="field_listingtype" class="lbl-ui select">
        <select id="field_category_select" name="type" class="pf-special-selectbox" data-pf-plc="<?= Yii::t('widgets', 'Category') ?>:">
            <option></option>

            <?php foreach ($categories as $id => $cat) : ?>
                <?php if ($id == $selected) :?>
                <option selected value="<?= $id ?>"><?= $cat ?></option>
                <?php else :?>
                <option value="<?= $id ?>"><?= $cat ?></option>
                <?php endif;?>
            <?php endforeach; ?>

        </select>
    </label>
</div>
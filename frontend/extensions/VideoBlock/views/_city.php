<?php
/**
 * @var $this \yii\web\View
 * @var $cities array
 */
?>

<div>
    <label for="field_listingtype" class="lbl-ui select">
        <select id="field_city_select" name="id_city" class="pf-special-selectbox" data-pf-plc="<?= Yii::t('widgets', 'City') ?>:">
            <option></option>

            <?php foreach ($cities as $id => $title) : ?>
                <option value="<?= $id ?>"><?= $title ?></option>
            <?php endforeach; ?>

        </select>
    </label>
</div>
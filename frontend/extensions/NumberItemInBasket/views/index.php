<?php
/**
 *@var integer $numberItems number items in basket
 */
?>
<div class="basket-number" <?= $numberItems ?  '' : 'style="display: none;"' ?>>
    <div class="basket-number-text" id="totalCount"><?= $numberItems ?></div>
</div>
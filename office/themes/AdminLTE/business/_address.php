<?php
/**
 * @var $address \common\models\BusinessAddress[]
 */
?>
<li data-count="<?=$count?>" class="address_list" > <a href="#" class="view"><?=$address['address']?></a>
    <a href="#" class="delete"><small style="color:red;">X</small></a>
    <div class="address_block">
        <?= Yii::t('business', 'Address')?><input type="text" class="form-control business_address" name="business_address[<?=$count?>][address]" value="<?=$address['address']?>"/>
        <?= Yii::t('business', 'Phone')?><input type="text" class="form-control business_phone" name="business_address[<?=$count?>][phone]" value="<?=$address['phone']?>"/>
        <div style="display: none;">
            <?= Yii::t('business', 'Lat')?><input type="text" class="form-control business_lat" name="business_address[<?=$count?>][lat]" value="<?=$address['lat']?>"/>
            <?= Yii::t('business', 'Lon')?><input type="text" class="form-control business_lon" name="business_address[<?=$count?>][lon]" value="<?=$address['lon']?>"/>
        </div>
        <input type="hidden" name="business_address[<?=$count?>][id]" value="<?=$address['id']?>"/>
        <input type="hidden" name="business_address[<?=$count?>][city]" value="<?=$address['city']?>"/>
    </div>
</li> 
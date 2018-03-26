<?php
/**
 * @var $address \common\models\BusinessAddress[]
 */
?>
<li data-count="<?= $count ?>" class="address_list"><a href="#"
                                                       class="view"><?= '<span class="glyphicon glyphicon-map-marker"></span>', str_replace('\\', '', $address['address']) ?></a>
    <a href="#" class="delete">
        <small style="color:red;">X</small>
    </a>
    <div class="address_block">
        Адрес:
        <button type="button" class="check-address btn btn-success btn-xs">Проверить адрес</button>
        (Введите адрес в таком виде: улица Соборная 25, Киев. Или кликните по объекту на карте)
        <button type="button" class="get-address btn btn-info btn-xs">Заполнить адрес</button>
        <input type="text" class="form-control business_address" name="business_address[<?= $count ?>][address]"
               value="<?= htmlspecialchars(str_replace('\\', '', $address['address'])) ?>"/>
        Телефон:<input type="text" class="form-control business_phone" name="business_address[<?= $count ?>][phone]"
                       value="<?= $address['phone'] ?>"/>
        График работы:<input type="text" class="form-control business_working_time"
                             name="business_address[<?= $count ?>][working_time]"
                             value="<?= $address['working_time'] ?>"/>
        <div style="display: none;">
            Широта:<input type="text" class="form-control business_lat" name="business_address[<?= $count ?>][lat]"
                          value="<?= ($address['lat']) ? $address['lat'] : '' ?>"/>
            Долгота:<input type="text" class="form-control business_lon" name="business_address[<?= $count ?>][lon]"
                           value="<?= ($address['lon']) ? $address['lon'] : '' ?>"/>
            <input type="hidden" name="business_address[<?= $count ?>][id]" value="<?= $address['id'] ?>"/>
            <input type="hidden" name="business_address[<?=$count?>][city]" value="<?= $address['city'] ?>"/>
        </div>
    </div>
</li> 
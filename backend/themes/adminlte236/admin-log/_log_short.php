<tr>
    <td width="120px"><?= $key?></td>
    
    <?php foreach ($this->context->listUser as $user): ?>  
        <td> 
            <?= array_key_exists($user, $model)? $model[$user] : ''; ?>
        </td>
    <?php endforeach;?>

</tr>
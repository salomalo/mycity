<?xml version="1.0" encoding="utf-8"?>

<sphinx:docset>
    <sphinx:schema>
        <sphinx:attr name="_id" type="string"/>
        <sphinx:field name="title"/>
        <sphinx:field name="description"/>
        <sphinx:field name="id_city"/>
        <sphinx:attr name="model_name"/>
    </sphinx:schema>

<?php

//$m = new \Mongo();
//$c = $m->test->documents;
//$list = $c->find();

$m = new \MongoClient();
$db = $m->selectDB('mycity');
$collection = new MongoCollection($db, 'ads');
$cursor = $collection->find();
$list = iterator_to_array($cursor);

$type = 6;
?>

<?php $i = 0; foreach ( $list as $document ) : ?>
   
    <!--<sphinx:document id="<?$document['_id']?>">-->
    <sphinx:document id="<?=$i + 1?>">
    <_id><?=$document['_id']?></_id>
    <title><![CDATA[[<?=$document['title']?>]]></title>
    <description><![CDATA[[<?=$document['description']?>]]></description>
    <id_city><![CDATA[[<?= !empty($document['idCity'])? $document['idCity'] : 0 ?>]]></id_city>
    <model_name><![CDATA[[<?= $type ?>]]></model_name>
    
    </sphinx:document>
<?php endforeach; ?>

</sphinx:docset>
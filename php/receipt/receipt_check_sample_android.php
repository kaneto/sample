<?php
require_once('./lib/receipt_android.class.php');

$signed_data = $_POST['signed_data'];
$signature = $_POST['signature'];

$rsa_key = '';


$receipt_obj = new ReceiptAndroid($rsa_key);
$receipt_obj->setSignedData($signed_data);
$receipt_obj->setSignature($signature);
$result = $receipt_obj->exec();

var_dump($result);

?>

<?php

require_once('./lib/receipt_ios.class.php');

$receipt_data = $_POST['receipt_data'];


$receipt_obj = new ReceiptIos();
$receipt_obj->setBase64Encode(false);
$receipt_obj->setReceipt($receipt_data);
$response = $receipt_obj->exec();

var_dump($response);

?>

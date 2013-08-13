<?php

// レシートデータ
$receipt_data = $_POST['receipt-data'];


// 整形
$post_data = json_encode(array("receipt-data" => base64_encode($receipt_data)));


// 課金サーバ
$url = "https://sandbox.itunes.apple.com/verifyReceipt";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json; charset=UTF-8'));
curl_setopt($ch, CURLOPT_HEADER, FALSE);

$response = curl_exec($ch);
$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 通信結果
var_dump($response);
var_dump($response_code);




echo "\n\n--------------------DEBUG---------------------\n\n";

echo "\nPOST_DATA\n";
var_dump($post_data);

$data1 = base64_decode($receipt_data);

//var_dump(json_encode($data1));

$data2 = $data1;
$data2 = preg_replace('/" = "/i', '":"', $data2);
$data2 = str_replace(array('";'), '",', $data2);
$data2 = str_replace(array("\t", "\n"), '', $data2);
$data2 = str_replace(array(',}'), '}', $data2);
var_dump($data2);

$data3 = json_decode($data2, true);
echo "\nRECEIPT_DATA\n";
var_dump($data3);


$data4 = base64_decode($data3['purchase-info']);
echo "\nPURCHASE-INFO\n";
var_dump($data4);


?>

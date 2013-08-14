<?php

$signed_data = $_POST['signed_data'];
$signature = $_POST['signature'];

$rsa_key = '';


$cert = "-----BEGIN PUBLIC KEY-----" . PHP_EOL . chunk_split($rsa_key, 64, PHP_EOL) . "-----END PUBLIC KEY-----";


$pubkey = openssl_get_publickey($cert);

$signature = base64_decode($signature);

$result = openssl_verify($signed_data, $signature, $pubkey, OPENSSL_ALGO_SHA1);

openssl_free_key($pubkey);


var_dump($result);

?>

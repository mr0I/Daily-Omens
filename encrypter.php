<?php

define('AES_METHOD', 'aes-256-cbc');
$password = 'lbwyBzfgzUIvXZFShJuikaWvLJhIVq36';

if (OPENSSL_VERSION_NUMBER <= 268443727) {
    throw new RuntimeException('OpenSSL Version too old, vulnerability to Heartbleed');
}

$iv_size        = openssl_cipher_iv_length(AES_METHOD);
$iv             = openssl_random_pseudo_bytes($iv_size);
$ciphertext     = openssl_encrypt(time() . ';SimpleOmen', AES_METHOD, $password, OPENSSL_RAW_DATA, $iv);
$ciphertext_hex = bin2hex($ciphertext);
$iv_hex         = bin2hex($iv);
echo "$iv_hex:$ciphertext_hex";

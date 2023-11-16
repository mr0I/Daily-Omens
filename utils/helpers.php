<?php defined('ABSPATH') or die('No script kiddies please!');


function validateRestApiToken($token, $password, $type)
{
    $iv_size    = openssl_cipher_iv_length(AES_METHOD);
    $data       = explode(":", $token);
    $iv         = hex2bin($data[0]);
    $cipherText = hex2bin($data[1]);
    $reqToken   = openssl_decrypt($cipherText, AES_METHOD, $password, OPENSSL_RAW_DATA, $iv);
    $explode = explode(';', $reqToken);

    if ($explode[1] !== $type || time() - $explode[0] > 60) {
        return 0;
    }
    return 1;
}

<?php

$constants = [
    'AES_PASSWORD' => 'AES_PASSWORD',
    'TOKEN_EXPIRE_TIME' => 'TOKEN_EXPIRE_TIME'
];

foreach ($constants as $key => $value) {
    putenv("$key=$value");
}

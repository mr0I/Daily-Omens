<?php

$constants = [
    'AES_PASSWORD' => 'AES_PASSWORD'
];

foreach ($constants as $key => $value) {
    putenv("$key=$value");
}

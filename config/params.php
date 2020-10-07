<?php

use kartik\datecontrol\Module;

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'confirmvmmarr@gmail.com',
    'senderName' => 'Example.com mailer',
    'smtpUsername' => 'confirmvmmarr@gmail.com',
    'bsVersion' => '4.x',
    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'php:d-m-Y',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:d-m-Y H:i:s',
    ],

    // format settings for saving each date attribute (PHP format example)
    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],
];

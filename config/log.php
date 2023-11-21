<?php
return [
    'default' => 'file',

    'database' => [
        'table' => 'logs',
        'class' => \app\modules\logger\factories\DatabaseLogger::class
    ],
    'file' => [
        'path' => __DIR__ . '/logs',
        'class' => \app\modules\logger\factories\FileLogger::class
    ],
    'mail' => [
        'email' => 'example@mail.com',
        'subject' => 'Logs',
        'class' => \app\modules\logger\factories\MailLogger::class
    ]
];
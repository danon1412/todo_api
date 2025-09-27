<?php
return [
    'database' => [
        'type' => 'sqlite',
        'name' => __DIR__ . '/tasks.db',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    ]
];
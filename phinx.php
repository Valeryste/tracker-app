<?php

require __DIR__ . '/vendor/autoload.php';

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => getenv('DB_HOST'),
                'name' => getenv('MYSQL_DATABASE'),
                'user' => getenv('MYSQL_USER'),
                'pass' => getenv('MYSQL_PASSWORD'),
                'port' => getenv('DB_PORT'),
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => getenv('DB_HOST'),
                'name' => getenv('MYSQL_DATABASE') . '_test',
                'user' => getenv('MYSQL_USER'),
                'pass' => getenv('MYSQL_PASSWORD'),
                'port' => getenv('DB_PORT'),
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation'
    ];
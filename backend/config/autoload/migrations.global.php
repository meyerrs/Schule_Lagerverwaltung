<?php
return [
    'doctrine' => [
        'migrations' => [
            'table_storage' => ['table_name' => 'doctrine_migration_versions'],
            'migrations_paths' => ['Migrations' => 'data/migrations'],
        ],
    ],
];
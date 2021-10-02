<?php

require '../vendor/autoload.php';

use Bubu\Database\Actions\CreateColumn;
use Bubu\Database\Actions\CreateTable;
use Bubu\Database\Database;
use Bubu\Database\QueryBuilder\QueryBuilder;

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();
$dotenv->required(['DB_USERNAME', 'DB_PASSWORD', 'DB_NAME', 'DB_HOST', 'DB_PORT']);

var_dump(Database::queryBuilder('testtt')
    ->delete()
    ->where([
        'col1',
        [':c' => 60],
        '>='
    ])
    ->execute()
);
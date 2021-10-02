<?php

require '../vendor/autoload.php';

use Bubu\Database\Actions\CreateColumn;
use Bubu\Database\Actions\CreateTable;
use Bubu\Database\Database;


$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();
$dotenv->required(['DB_USERNAME', 'DB_PASSWORD', 'DB_NAME', 'DB_HOST', 'DB_PORT']);

Database::createTable('test')->addColumn(
    Database::createColumn('col1')
        ->type(CreateColumn::INT)
        ->size(10)
        ->defaultValue(30)
        ->comments('Bon ok')
        ->notNull()
)
->addColumn(
    Database::createColumn('id')
        ->type(CreateColumn::INT)
        ->size(11)
        ->autoIncrement()
)
->addIndex([
    'type' => 'primary',
    'columns' => ['id'],
    'name' => 'test'
])
->addIndex([
    'type' => CreateTable::UNIQUE_INDEX,
    'columns' => ['col1', 'id'],
    'name' => 'uniqueeee'
])
->simulate()
->execute();

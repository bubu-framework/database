<?php

require '../vendor/autoload.php';

use Bubu\Database\Actions\CreateColumn;
use Bubu\Database\Actions\CreateTable;
use Bubu\Database\Database;
use Bubu\Database\QueryBuilder\QueryBuilder;

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();
$dotenv->required(['DB_USERNAME', 'DB_PASSWORD', 'DB_NAME', 'DB_HOST', 'DB_PORT']);

Database::createTable('test')
    ->ifNotExists()
    ->addColumn(
        Database::createColumn('col1')
            ->type(CreateColumn::INT)
            ->size(10)
            ->defaultValue(30)
            ->comments("It's ok")
            ->notNull()
    )->addColumn(
        Database::createColumn('col2')
            ->type(CreateColumn::TEXT)
    )->addColumn(
        Database::createColumn('id')
            ->type(CreateColumn::INT)
            ->size(11)
            ->autoIncrement()
    )->addIndex([
        'type' => 'primary',
        'columns' => ['id'],
        'name' => 'primaryKey'
    ])->addIndex([
        'type' => CreateTable::UNIQUE_INDEX,
        'columns' => ['col1', 'id'],
        'name' => 'uniqueKey'
    ])
    ->execute();

Database::queryBuilder('test')
    ->insert([
        'col1' => 60,
        'col2' => 'Super!'
    ])
    ->execute();

Database::queryBuilder('test')
    ->insert([
        'col1' => 50,
        'col2' => 'Super2!'
    ])
    ->execute();

var_dump(Database::queryBuilder('test')
    ->select('col1', 'col2')
    ->orderBy('col1', QueryBuilder::DESC)
    ->limit(2)
    ->where(
        Database::expr()->eq('col1', ':col', 50)
    )
    ->fetchAll());
/*
    Database::queryBuilder('test')
    ->delete()
    ->where([
        'col1',
        [':col' => 60],
        '>='
    ])
    ->execute();*/

Database::queryBuilder('test')
    ->delete()
    ->where(
        Database::expr()::lte('col1', ':col', 50)
    )
    ->execute();

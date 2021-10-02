# Database library (bubu framework)

## Installation with composer

```bash
$ composer require bubu-framework/database
```

### Configuration

Follow .env.example syntaxe

## Usage

___

### Create table

Call Database CreateTable const for create table, setup him and call execute function for create the table. Call addColumn method for create a column and add to table.

- _Example_:

    ```php
    Database::createTable('test')->addColumn(
        Database::createColumn('col1')
        ->type(CreateColumn::INT)
        ->size(10)
        ->defaultValue(30)
        ->comments("It's ok")
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
            'name' => 'primaryKey'
        ])
        ->addIndex([
            'type' => CreateTable::UNIQUE_INDEX,
            'columns' => ['col1', 'id'],
            'name' => 'uniqueKey'
        ])
        ->execute();
    ```

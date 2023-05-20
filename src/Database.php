<?php
namespace Bubu\Database;

use \PDO;
use Bubu\Database\Actions\CreateTable;
use Bubu\Database\Actions\CreateColumn;
use Bubu\Database\QueryBuilder\Expr;
use Bubu\Database\QueryBuilder\QueryBuilder;

class Database
{
    public const ERRMODE_SILENT      = PDO::ERRMODE_SILENT;
    public const ERRORMODE_WARNING   = PDO::ERRMODE_WARNING;
    public const ERRORMODE_EXCEPTION = PDO::ERRMODE_EXCEPTION;

    public const FETCH     = 'fetch';
    public const FETCH_ALL = 'fetchAll';

    public const FETCH_ASSOC      = PDO::FETCH_ASSOC;
    public const FETCH_BOTH       = PDO::FETCH_BOTH;
    public const FETCH_BOUND      = PDO::FETCH_BOUND;
    public const FETCH_CLASS      = PDO::FETCH_CLASS;
    public const FETCH_INTO       = PDO::FETCH_INTO;
    public const FETCH_LAZY       = PDO::FETCH_LAZY;
    public const FETCH_NAMED      = PDO::FETCH_NAMED;
    public const FETCH_NUM        = PDO::FETCH_NUM;
    public const FETCH_OBJ        = PDO::FETCH_OBJ;
    public const FETCH_PROPS_LATE = PDO::FETCH_PROPS_LATE;

    private PDO $pdo;

    private function __construct(?array $dbConnectionOpt =  null)
    {
        $type = $dbConnectionOpt['type'] ?? $_ENV['DB_TYPE'];
        $host = $dbConnectionOpt['host'] ?? $_ENV['DB_HOST'];
        $name = $dbConnectionOpt['name'] ?? $_ENV['DB_NAME'];
        $port = $dbConnectionOpt['port'] ?? $_ENV['DB_PORT'];
        $username = $dbConnectionOpt['username'] ?? $_ENV['DB_USERNAME'];
        $password = $dbConnectionOpt['password'] ?? $_ENV['DB_PASSWORD'];
        $errorMode = $dbConnectionOpt['errorMode'] ?? $_ENV['DB_ERRORMODE'];

        $fetchMode = $dbConnectionOpt['fetchMode'] ?? constant("self::{$_ENV['DB_FETCH_MODE']}");

        $pdo = new PDO(
            $type . ':host=' . $host . ';dbname=' . $name . ';port=' . $port,
            $username,
            $password
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, $errorMode);
        $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);

        $this->pdo = $pdo;
        return $this;
    }

    public static function createConnection(?array $dbConnectionOpt = null): Database
    {
        return new Database($dbConnectionOpt);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @param string $name
     * @return CreateColumn
     */
    public static function createColumn(string $name): CreateColumn
    {
        return new CreateColumn($name);
    }

    /**
     * @param string $name
     * @return CreateTable
     */
    public static function createTable(string $name): CreateTable
    {
        return new CreateTable($name);
    }

    /**
     * @param string $table Table name
     * @return QueryBuilder
     */
    public static function queryBuilder(string $table): QueryBuilder
    {
        return new QueryBuilder($table);
    }

    /**
     * expr
     *
     * @return Expr
     */
    public static function expr(): Expr
    {
        return new Expr();
    }
}
<?php

namespace Bubu\Database;

class DatabaseQueryBuilder extends Database
{
    use QueryMethods;

    /**
     * @var string $request
     * @var array $params
     */
    protected array $params = [];
    protected static array $required = ['table'];

    /**
     * @param string $table Table name
     */
    public function __construct(string $table)
    {
        $this->table($table);
    }

    /**
     * @return DatabaseQueryBuilder
     */
    public function debug(): DatabaseQueryBuilder
    {
        var_dump(
            '<pre>',
            $this,
            '</pre>'
        );
        return $this;
    }

    /**
     * @return DatabaseQueryBuilder
     */
    public function debugRequest(): DatabaseQueryBuilder
    {
        var_dump(
            '<pre>',
            '--------------- CLASS ---------------',
            $this,
            '---------- debugDumpParams ----------',
            $this->request->debugDumpParams(),
            '------------- errorInfo -------------',
            $this->request->errorInfo(),
            '</pre>'
        );
        return $this;
    }

    /**
     * @return DatabaseQueryBuilder
     */
    public function simulate(): DatabaseQueryBuilder
    {
        echo $this->build();
        return $this;
    }

    /**
     * @param string $mode
     * @return int
     */
    private static function fetchMode(string $mode): int
    {
        $mode = strtoupper($mode);
        return constant("PDO::FETCH_$mode");
    }

    /**
     * @param mixed $mode
     * @return array
     */
    public function execute(): bool
    {
        $request = $this->build();
        return Database::request(
            $request,
            $this->values,
        );
    }

    /**
     * @param mixed $mode
     * @return array
     */
    public function fetch(string $mode = 'ASSOC')
    {
        $mode = self::fetchMode($mode);
        $request = $this->build();
        return Database::request(
            $request,
            $this->values,
            Database::FETCH,
            $mode
        );
    }
    
    /**
     * @param mixed $mode
     * @return array
     */
    public function fetchAll(string $mode = 'ASSOC')
    {
        $mode = self::fetchMode($mode);
        $request = $this->build();
        return Database::request(
            $request,
            $this->values,
            Database::FETCH_ALL,
            $mode
        );
    }
}

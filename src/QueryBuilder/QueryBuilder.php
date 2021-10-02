<?php
namespace Bubu\Database\QueryBuilder;

use Bubu\Database\Database;
use Bubu\Database\DatabaseRequest;

class QueryBuilder implements QueryMethodsInterface
{
    use QueryMethods;

    public const ASC = 'ASC';
    public const DSC = 'DSC';

    protected $params = [];
    protected static $required = ['table'];

    /**
     * @param string $table Table name
     */
    public function __construct(string $table)
    {
        $this->table($table);
    }

    /**
     * @return self
     */
    public function simulate(): self
    {
        echo $this;
        return $this;
    }

    public function __toString(): string
    {
       $request = str_replace('[TABLE_NAME]', $this->table, $this->action);

       if (!is_null($this->condition)) $request .= $this->condition;
       if (!is_null($this->orderBy)) $request .= $this->orderBy;
       if (!is_null($this->limit)) $request .= $this->limit;

       return $request;
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
    public function execute(string $returnMode, string $fetchType): bool
    {
        $mode = self::fetchMode($returnMode);
        return DatabaseRequest::request(
            $this,
            $this->values,
            $fetchType,
            $mode
        );
    }

    /**
     * @param mixed $mode
     * @return array
     */
    public function fetch(string $mode = 'ASSOC')
    {
        $this->execute($mode, Database::FETCH_ALL);
    }

    /**
     * @param mixed $mode
     * @return array
     */
    public function fetchAll(string $mode = 'ASSOC')
    {
        $this->execute($mode, Database::FETCH);
    }
}

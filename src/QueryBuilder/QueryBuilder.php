<?php
namespace Bubu\Database\QueryBuilder;

use Bubu\Database\Database;
use Bubu\Database\DatabaseRequest;

class QueryBuilder implements QueryMethodsInterface
{
    use QueryMethods;

    public const ASC = 'ASC';
    public const DESC = 'DESC';

    public const SECURE = "_bubu_fw_end_secure_";

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
       if (!is_null($this->in)) $request .= (is_null($this->condition) ? 'WHERE ' : ' AND ') . implode(' AND ', $this->in);
       if (!is_null($this->orderBy)) $request .= $this->orderBy;
       if (!is_null($this->limit)) $request .= $this->limit;

       return $request;
    }

    /**
     * execute request
     * 
     * @param string $fetchType
     * @param Database|null $dbInstance
     * @param array $opt
     * @return mixed
     */
    private function exec(
        string $fetchType = '',
        ?Database $dbInstance = null,
        array $opt = []
    ): mixed {
        return DatabaseRequest::request(
            $this,
            $this->values,
            $fetchType,
            $dbInstance,
            $opt
        );
    }

    /**
     * @param Database|null $dbInstance
     * @param array $opt
     * @return array|bool
     */
    public function fetch(
        ?Database $dbInstance = null,
        array $opt = []
    ): mixed {
        return $this->exec(Database::FETCH, $dbInstance, $opt);
    }

    /**
     * @param Database|null $dbInstance
     * @param array $opt
     * @return array|bool
     */
    public function fetchAll(
        ?Database $dbInstance = null,
        array $opt = []
    ): mixed {
        return $this->exec(Database::FETCH_ALL, $dbInstance, $opt);
    }

    /**
     * @param Database|null $dbInstance
     * @param array $opt
     * @return mixed
     */
    public function execute(?Database $dbInstance = null, array $opt = []): mixed
    {
        return $this->exec('', $dbInstance, $opt);
    }
}

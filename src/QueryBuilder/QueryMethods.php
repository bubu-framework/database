<?php
namespace Bubu\Database\QueryBuilder;

trait QueryMethods
{
    protected $table;
    protected $as;
    protected $action;
    protected $condition;
    protected $set;
    protected $values = [];
    protected $orderBy;
    protected $limit;

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param array $values ['column name' => 'value']
     * @return self
     */
    public function insert(array $values): self
    {
        $columns = '';
        $markers = '';
        foreach ($values as $key => $value) {
            $columns .= "`{$key}`, ";
            $markers .= ":{$key}, ";
            $this->values[
                $key
            ] = $value;
        }
        $columns = trim($columns, ', ');
        $markers = trim($markers, ', ');
        $request = 'INSERT INTO `[TABLE_NAME]` (' . $columns . ') VALUES (' . $markers . ')';
        $this->action = $request;
        return $this;
    }

    /**
     * @param string $columns
     * @return self
     */
    public function select(string ...$columns): self
    {
        $select = 'SELECT';
        foreach ($columns as $value) {
            if ($value === '*') {
                $select .= ' *,';
            } else {
                $select .= " `{$value}`,";
            }
        }
        $this->action = rtrim($select, ',') . ' FROM `[TABLE_NAME]`';
        return $this;
    }

    /**
     * @return self
     */
    public function delete(): self
    {
        $this->action = 'DELETE FROM `[TABLE_NAME]` ';
        return $this;
    }

    /**
     * @return self
     */
    public function update(array $values): self
    {
        $request = '';
        foreach ($values as $key => $value) {
            $request .= "`{$key}` = :{$key}, ";
            $this->values[
                $key
            ] = $value;
        }
        $request = trim($request, ', ');
        $this->action = 'UPDATE `[TABLE_NAME]` SET ' . $request;
        return $this;
    }

    /**
     * @param array $where
     * ->where(
     *      [
     *          '<col>',
     *          ['<marker>' => <value>],
     *          ('<operator>')
     *      ]
     * )
     * @return self
     */
    public function where(array ...$where): self
    {
        if (is_null($this->condition)) {
            $condition = ' WHERE (';
        } else {
            $condition = $this->condition . ' OR (';
        }
        foreach ($where as $value) {
            $marker = key($value[1]);
            if ($marker !== '?') $marker = ':' . ltrim($marker, ':');
            if (isset($value[1])) {
                $i = 0;
                while (array_key_exists("{$marker}{$i}", $this->values)) {
                    $i++;
                }
                $this->values[
                    "{$marker}{$i}"
                    ] = $value[1][key($value[1])];
            }
            if ($marker !== '?') $marker = $marker . $i;
            $condition .= "`{$value[0]}` " . (isset($value[2]) ? "{$value[2]} " : '= ') . "{$marker} AND ";
        }
        $condition = rtrim($condition, ' AND ');
        $condition .= ')';
        $this->condition = $condition;
        return $this;
    }

    /**
     * order by
     *
     * @param string $column
     * @param string $order
     * @return self
     */
    public function orderBy(string $column, string $order = QueryBuilder::ASC): self
    {
        $this->orderBy = " ORDER BY `$column` $order";
        return $this;
    }

    /**
     * limit
     *
     * @param integer $limit
     * @param integer $offset
     * @return self
     */
    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = " LIMIT $limit OFFSET $offset";
        return $this;
    }
}

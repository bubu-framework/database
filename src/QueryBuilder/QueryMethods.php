<?php

namespace Bubu\Database\QueryBuilder;

trait QueryMethods
{
    protected string $table;
    protected ?string $as        = null;
    protected string $action;
    protected ?string $condition = null;
    protected ?string $set       = null;
    protected array  $values     = [];
    protected ?string $orderBy   = null;
    protected ?string $limit     = null;
    protected ?string $join      = null;

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
            $this->values[$key] = $value;
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
            } elseif (str_contains($value, '.*')) {
                $select .= ' `' . explode('.', $value)[0] . '`.*,';
            } elseif (str_contains($value, '.')) {
                $select .=  ' `' . explode('.', $value)[0] . '`.`' . explode('.', $value)[1] . '`,';
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
     * $values = [
     * 'col' => 'val'
     * ]
     * @return self
     */
    public function update(array $values): self
    {
        $request = '';
        foreach ($values as $col => $value) {
            $marker = $col . QueryBuilder::SECURE . count($this->values);

            $request .= "`{$col}` = :{$marker}, ";

            $this->values[$marker] = $value;
        }
        $request = trim($request, ', ');
        $this->action = 'UPDATE `[TABLE_NAME]` SET ' . $request;
        return $this;
    }

    /**
     * @param array $where
     * ->where(
     *      Database::expr()::<op>('<column>', '<value>')
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

            $marker = ':' . str_replace('.', '_', $value['column']) . QueryBuilder::SECURE . count($this->values);
            $tmpExpr = explode(' ', $value['expr']);
            $tmpExpr[count($tmpExpr) - 1] = str_replace('.', '_', $tmpExpr[count($tmpExpr) - 1]);
            $value['expr'] = implode(' ', $tmpExpr);
            $condition .= $value['expr']
                . QueryBuilder::SECURE
                . count($this->values)
                . (str_contains($value['expr'], '` IN (:' . $value['column']) ? ')' : '')
                . " AND ";

            $this->values[$marker] = $value['value'];
        }
        $condition = rtrim($condition, ' AND ') . ')';
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

    /**
     * join
     *
     * @param string $table
     * @param string $method
     * @param string $col1
     * @param string $col2
     * 
     * @return self
     */
    public function join(
        string $table,
        string $method = QueryBuilder::INNER,
        string $col1,
        string $col2
    ): self {
        $this->join .= " $method JOIN `$table` ON `" . implode('`.`', explode('.', $col1)) . "` = `" . implode('`.`', explode('.', $col2)) . "`";
        return $this;
    }
}

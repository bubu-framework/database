<?php
namespace Bubu\Database\QueryBuilder;

interface QueryMethodsInterface
{
    public function table(string $table): self;

    public function insert(array $values): self;

    public function select(string ...$columns): self;

    public function delete(): self;

    public function update(array $values): self;

    public function where(array ...$where): self;

    public function orderBy(string $column, string $order = QueryBuilder::ASC): self;

    public function limit(int $limit, int $offset = 0): self;

    public function join(string $table, string $mode, string $col1, string $col2): self;
}

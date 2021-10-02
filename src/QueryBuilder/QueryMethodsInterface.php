<?php
namespace Bubu\Database\QueryBuilder;

interface QueryMethodsInterface
{

    public const ASC = 'ASC';
    public const DSC = 'DSC';

    public function table(string $table): self;

    public function insert(array $values): self;

    public function select(array ...$columns): self;

    public function delete(): self;

    public function update(array $values): self;

    public function where(array ...$where): self;

    public function orderBy(string $column, string $order = self::ASC): self;

    public function limit(int $limit, int $offset = 0): self;
}

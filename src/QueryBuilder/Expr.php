<?php

namespace Bubu\Database\QueryBuilder;

class Expr
{
    /**
     * eq (=)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function eq(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` = :{$column}"
        ];
    }

    /**
     * neq (<>)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function neq(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` <> :{$column}"
        ];
    }

    /**
     * lt (<)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function lt(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` < :{$column}"
        ];
    }

    /**
     * lte (<=)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function lte(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` <= :{$column}"
        ];
    }

    /**
     * gt (>)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function gt(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` > :{$column}"
        ];
    }

    /**
     * gte (>=)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function gte(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` >= :{$column}"
        ];
    }

    /**
     * isNull (IS NULL)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function isNull(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` IS NULL :{$column}"
        ];
    }

    /**
     * isNotNull (IS NOT NULL)
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function isNotNull(string $column, mixed $value): array {
        return [
            'value'  => $value,
            'column' => $column,
            'expr'   => "`{$column}` IS NOT NULL :{$column}"
        ];
    }

    /**
     * in (IN)
     *
     * @param string $column
     * @param array $value
     * @return array
     */
    public static function in(string $column, array $value): array {
        return [
            'value'  => '(' . implode(',', $value) . ')',
            'column' => $column,
            'expr'   => "`{$column}` IN (:{$column}"
        ];
    }
}

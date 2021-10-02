<?php

namespace Bubu\Database\QueryBuilder;

class Expr
{
    /**
     * eq (=)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function eq(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` = {$marker}"
        ];
    }

    /**
     * neq (<>)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function neq(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` <> {$marker}"
        ];
    }

    /**
     * lt (<)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function lt(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` < {$marker}"
        ];
    }

    /**
     * lte (<=)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function lte(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` <= {$marker}"
        ];
    }

    /**
     * gt (>)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function gt(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` > {$marker}"
        ];
    }

    /**
     * gte (>=)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function gte(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` >= {$marker}"
        ];
    }

    /**
     * isNull (IS NULL)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function isNull(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` IS NULL {$marker}"
        ];
    }

    /**
     * isNotNull (IS NOT NULL)
     *
     * @param string $column
     * @param string $marker
     * @param mixed $value
     * @return array
     */
    public static function isNotNull(string $column, string $marker, mixed $value): array {
        return [
            'marker' => $marker,
            'value'  => $value,
            'expr'   => "`{$column}` IS NOT NULL {$marker}"
        ];
    }
}

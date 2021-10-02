<?php
namespace Bubu\Database\Actions;

use Bubu\Database\Exception\DatabaseException;

use function PHPSTORM_META\type;

class CreateColumn
{

    public const TINY_INT    = 'tinyint';
    public const SMALL_INT   = 'smallint';
    public const MEDIUM_INT  = 'mediumint';
    public const INT         = 'int';
    public const BIG_INT     = 'bigint';
    public const BIT         = 'bit';

    public const FLOAT       = 'float';
    public const DOUBLE      = 'double';
    public const DECIMAL     = 'decimal';

    public const VARCHAR     = 'varchar';
    public const CHAR        = 'char';
    public const TINY_TEXT   = 'tinytext';
    public const TEXT        = 'text';
    public const MEDIUM_TEXT = 'mediumtext';
    public const LONG_TEXT   = 'longtext';
    public const JSON        = 'json';

    public const BINARY      = 'binary';
    public const VAR_BINARY  = 'varbinary';
    public const TINY_BLOB   = 'tinyblob';
    public const BLOB        = 'blob';
    public const MEDIUM_BLOB = 'mediumblob';
    public const LONG_BLOB   = 'longblob';

    public const DATE        = 'date';
    public const TIME        = 'time';
    public const YEAR        = 'year';
    public const DAY_TIME    = 'daytime';
    public const TIMESTAMP   = 'timestamp';


    private $name;
    private $type;
    private $size;
    private $unsigned = false;
    private $zerofill = false;
    private $notNull = false;
    private $autoIncrement = false;
    private $defaultValue;
    private $comments;
    private $collate;
    private static $required = ['name', 'type'];

    /**
     * @param mixed $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * set type of the column
     *
     * @param string $type
     * @return self
     */
    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * set size of the column
     *
     * @param integer $size
     * @return self
     */
    public function size(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * if the column is unsigned
     *
     * @param boolean $unsigned
     * @return self
     */
    public function unsigned(bool $unsigned = true): self
    {
        $this->unsigned = $unsigned;
        return $this;
    }

    /**
     * zerofill for column
     *
     * @param boolean $zerofill
     * @return self
     */
    public function zerofill(bool $zerofill = true): self
    {
        $this->zerofill = $zerofill;
        return $this;
    }

    /**
     * if column should be not null
     *
     * @param boolean $notNull
     * @return self
     */
    public function notNull(bool $notNull = true): self
    {
        $this->notNull = $notNull;
        return $this;
    }

    /**
     * if column required auto increment
     *
     * @param boolean $autoIncrement
     * @return self
     */
    public function autoIncrement(bool $autoIncrement = true): self
    {
        $this->notNull();
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    /**
     * set default value to the column
     *
     * @param string|int $defaultValue
     * @return self
     */
    public function defaultValue(mixed $defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * put comments on the column
     *
     * @param string $comments
     * @return self
     */
    public function comments(string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * collate
     *
     * @param string $collate
     * @return self
     */
    public function collate(string $collate): self
    {
        $this->collate = $collate;
        return $this;
    }

    /**
     * @return self
     */
    public function simulate(): self
    {
        echo $this;
        return $this;
    }

    /**
     * @return string Return query with the new request
     */
    public function __toString(): string
    {
        foreach (self::$required as $require) {
            if (is_null($this->{$require})) {
                throw new DatabaseException('A variable required is null');
            }
        }

        return trim(
                "`{$this->name}`"
                    . ' '
                    . strtoupper($this->type)
                    . (!is_null($this->size) ? "({$this->size})" : '')
                    . ($this->unsigned ? ' UNSIGNED' : '')
                    . ($this->zerofill ? ' ZEROFILL' : '')
                    . ($this->notNull ? ' NOT NULL' : ' NULL')
                    . (!is_null($this->defaultValue) 
                        ? " DEFAULT '{$this->defaultValue}'"
                        : ($this->autoIncrement
                            ? ' AUTO_INCREMENT'
                            : ' DEFAULT NULL'
                        )
                    )
                    . (!is_null($this->comments) ? " COMMENT '{$this->comments}'" : '')
                    . (!is_null($this->collate) ? " COLLATE '{$this->collate}'" : '')
            );
    }
}
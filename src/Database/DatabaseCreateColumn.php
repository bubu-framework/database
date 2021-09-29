<?php

namespace Bubu\Database;

use Bubu\Exception\ShowException;
use Bubu\Database\DatabaseException;

/**
 * @method DatabaseCreateColumn type(string $type)
 * @method DatabaseCreateColumn size(int $size)
 * @method DatabaseCreateColumn unsigned(bool $unsigned)
 * @method DatabaseCreateColumn zerofill(bool $zerofill)
 * @method DatabaseCreateColumn notNull(bool $notNull)
 * @method DatabaseCreateColumn auto_increment(bool $auto_increment)
 * @method DatabaseCreateColumn defaultValue(array $defaultValue)
 * @method DatabaseCreateColumn comments(string $commets)
 * @method DatabaseCreateColumn collate(string $collate)
 * @return DatabaseCreateColumn
 * @throws DatabaseException
 */
class DatabaseCreateColumn
{

    /**
     * @var string $name Name of column
     * @var string $type Type of column
     * @var int|null $size Size of column
     * @var bool $unsigned If column should have UNSIGNED attribute
     * @var bool $zerofill If column should have ZEROFILL attribute
     * @var bool $notNull If column should have NOTNULL attribute
     * @var bool $auto_increment If column should have AUTO_INCREMENT attribute
     * @var array|null $defaultValue If column have a default value
     * @var string|null $comments Comments for column
     * @var string|null $collate If column should have a collate
     */
    protected string $name;
    protected string $type;
    protected $size;
    protected bool $unsigned = false;
    protected bool $zerofill = false;
    protected bool $notNull = false;
    protected bool $auto_increment = false;
    protected $defaultValue;
    protected $comments;
    protected $collate;
    protected static $required = ['name', 'type'];

    public $request;

    
    /**
     * @param mixed $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }
    
    /**
     * @return DatabaseCreateColumn
     */
    public function debug(): DatabaseCreateColumn
    {
        var_dump(
            '<pre>',
            $this,
            '</pre>'
        );
        return $this;
    }
    
    /**
     * @param mixed $name
     * @param mixed $arguments
     * @return DatabaseCreateColumn
     */
    public function __call($name, $arguments): DatabaseCreateColumn
    {
        try {
            if (array_key_exists($name, get_class_vars(get_class($this)))) {
                if (count($arguments) === 0) {
                    $this->{$name} = true;
                } elseif (count($arguments) === 1) {
                    $this->{$name} = $arguments[0];
                } else {
                    $this->{$name} = $arguments;
                }
                return $this;
            } else {
                throw new DatabaseException('Property not found.');
            }
        } catch (DatabaseException $e) {
            ShowException::SR($e);
        }
    }

    /**
     * @return string Return query with the new request
     */
    public function __toString(): string
    {
        try {
            foreach (self::$required as $require) {
                if (is_null($this->{$require})) {
                    throw new DatabaseException('A variable required is null');
                }
            }
            $request = 
                trim(
                    "`{$this->name}`"
                        . ' '
                        . strtoupper($this->type)
                        . (!is_null($this->size) ? "({$this->size})" : '')
                        . ($this->unsigned ? ' UNSIGNED' : '')
                        . ($this->zerofill ? ' ZEROFILL' : '')
                        . ($this->notNull ? ' NOT NULL' : ' NULL')
                        . ($this->auto_increment ? ' AUTO_INCREMENT' : '')
                        . (
                            !is_null($this->defaultValue)
                            ? ' DEFAULT' . 
                                (
                                    $this->defaultValue[1] === 'string'
                                    ? " '{$this->defaultValue[0]}'"
                                    : " {$this->defaultValue[0]}"
                                )
                            : '')


                        . (!is_null($this->comments) ? " COMMENT '{$this->comments}'" : '')
                        . (!is_null($this->collate) ? " COLLATE '{$this->collate}'" : '')
                    );
            return $request;
        } catch (DatabaseException $e) {
            ShowException::SR($e);
        }
    }
}

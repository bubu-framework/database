<?php
namespace Bubu\Database\Actions;

use Bubu\Database\Database;
use Bubu\Database\DatabaseRequest;
use Bubu\Database\Exception\DatabaseException;


class CreateTable
{
    public const PRIMARY_INDEX  = 'PRIMARY';
    public const UNIQUE_INDEX   = 'UNIQUE';
    public const FULLTEXT_INDEX = 'FULLTEXT';
    public const SPATIAL_INDEX  = 'SPATIAL';
    public const KEY_INDEX      = 'KEY';

    private bool   $ifNotExists = false;
    private string $name;
    private array  $allColumn   = [];
    private array  $allIndex    = [];
    private array  $foreignKey  = [];
    private string $collate     = 'utf8_general_ci';
    private ?string $comments   = null;
    private string $engine      = 'InnoDB';
    private static array $required = ['name'];
    
    /**
     * @param mixed $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * set if if not exists setting is required
     *
     * @param boolean $ifNotExists
     * @return self
     */
    public function ifNotExists(bool $ifNotExists = true): self
    {
        $this->ifNotExists = $ifNotExists;
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
     * put comments on the table
     *
     * @param string $comments
     * @return self
     */
    public function comments(string $comments): self
    {
        $comments = str_replace("'", "\'", $comments);
        $this->comments = $comments;
        return $this;
    }

    /**
     * set table engine
     *
     * @param string $engine
     * @return self
     */
    public function engine(string $engine): self
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * @param string $arguments
     * @return self
     */
    public function addColumn(string $arguments): self
    {
        $this->allColumn[] = $arguments;
        return $this;
    }

    /**
     * @param array $arguments
     * @return self
     */
    public function addIndex(array $arguments): self
    {
        $this->allIndex[] = 
            strtoupper($arguments['type'])
            . (strtoupper($arguments['type']) === 'PRIMARY' ? ' KEY ' : ' INDEX ')
            . "`{$arguments['name']}`"
            . ' (`'
            . implode('`,`', $arguments['columns'])
            . '`)';
        return $this;
    }

    /**
     * @return self
     */
    public function foreignKey($arguments): self
    {
        $this->foreignKey[] = 
            'CONSTRAINT '
            . "`{$arguments['name']}`"
            . ' FOREIGN KEY (`'
            . implode('`,`', $arguments['columns'])
            . '`) REFERENCES '
            . "`{$arguments['references']}` (`"
            . implode('`,`', $arguments['foreign'])
            . '`) '
            . (isset($arguments['on update']) ? 'ON UPDATE ' . strtoupper($arguments['on update']) . ' ' : '')
            . (isset($arguments['on delete']) ? 'ON DELETE ' . strtoupper($arguments['on delete']) . ' ' : '');
        return $this;
    }

    public function __toString(): string
    {
        foreach (self::$required as $require) {
            if (is_null($this->{$require})) throw new DatabaseException('A variable required is null');
        }

        return ($this->ifNotExists ? '' : "DROP TABLE IF EXISTS `{$this->name}`;")
            .'CREATE TABLE'
            . ($this->ifNotExists ? ' IF NOT EXISTS' : '')
            ." `{$this->name}` ("
            . implode(',', $this->allColumn)
            . (!empty($this->allIndex) ? ',' . implode(',', $this->allIndex) : '')
            . (!empty($this->foreignKey) ? ',' . implode(',', $this->foreignKey) : '')
            . ')'
            . (!is_null($this->comments) ? " COMMENT='{$this->comments}'" : '')
            . " COLLATE='{$this->collate}'"
            . " ENGINE={$this->engine}";
    }

    /**
     * show prepared request
     * @return self
     */
    public function simulate(): self
    {
        echo $this;
        return $this;
    }

    /**
     * @param Database|null $dbInstance
     * @param array $opt
     * @return void
     */
    public function execute(?Database $dbInstance = null, array $opt = []): void
    {
        DatabaseRequest::request($this, [], null, $dbInstance, $opt);
    }
}
<?php

namespace DUsmonov\LaravelModelGenerator\Model;

use Krlove\CodeGenerator\Model\ClassModel;

/**
 * Class EloquentModel
 * @package DUsmonov\LaravelModelGenerator\Model
 */
class EloquentModel extends ClassModel
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}

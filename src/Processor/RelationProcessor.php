<?php

namespace DUsmonov\LaravelModelGenerator\Processor;

use Illuminate\Database\DatabaseManager;
use DUsmonov\LaravelModelGenerator\Config\Config;
use DUsmonov\LaravelModelGenerator\Helper\EmgHelper;
use DUsmonov\LaravelModelGenerator\Helper\Prefix;
use DUsmonov\LaravelModelGenerator\Model\BelongsTo;
use DUsmonov\LaravelModelGenerator\Model\BelongsToMany;
use DUsmonov\LaravelModelGenerator\Model\EloquentModel;
use DUsmonov\LaravelModelGenerator\Model\HasMany;
use DUsmonov\LaravelModelGenerator\Model\HasOne;

class RelationProcessor implements ProcessorInterface
{
    public function __construct(private DatabaseManager $databaseManager) {}

    public function process(EloquentModel $model, Config $config): void
    {
        $schemaManager = $this->databaseManager->connection($config->getConnection())->getDoctrineSchemaManager();

        $prefixedTableName = Prefix::add($model->getTableName());
        $tables = $schemaManager->listTables();
        foreach ($tables as $table) {
            $foreignKeys = $schemaManager->listTableForeignKeys($table->getName());
            foreach ($foreignKeys as $name => $foreignKey) {
                $localColumns = $foreignKey->getLocalColumns();
                if (count($localColumns) !== 1) {
                    continue;
                }

                if ($table->getName() === $prefixedTableName) {
                    $relation = new BelongsTo(
                        Prefix::remove($foreignKey->getForeignTableName()),
                        $foreignKey->getLocalColumns()[0],
                        $foreignKey->getForeignColumns()[0]
                    );
                    $model->addRelation($relation);
                } elseif ($foreignKey->getForeignTableName() === $prefixedTableName) {
                    if (count($foreignKeys) === 2 && count($table->getColumns()) === 2) {
                        $keys = array_keys($foreignKeys);
                        $key = array_search($name, $keys) === 0 ? 1 : 0;
                        $secondForeignKey = $foreignKeys[$keys[$key]];
                        $secondForeignTable = Prefix::remove($secondForeignKey->getForeignTableName());

                        $relation = new BelongsToMany(
                            $secondForeignTable,
                            Prefix::remove($table->getName()),
                            $localColumns[0],
                            $secondForeignKey->getLocalColumns()[0]
                        );
                        $model->addRelation($relation);

                        break;
                    } else {
                        $tableName = Prefix::remove($table->getName());
                        $foreignColumn = $localColumns[0];
                        $localColumn = $foreignKey->getForeignColumns()[0];

                        if (EmgHelper::isColumnUnique($table, $foreignColumn)) {
                            $relation = new HasOne($tableName, $foreignColumn, $localColumn);
                        } else {
                            $relation = new HasMany($tableName, $foreignColumn, $localColumn);
                        }

                        $model->addRelation($relation);
                    }
                }
            }
        }
    }

    public function getPriority(): int
    {
        return 5;
    }
}

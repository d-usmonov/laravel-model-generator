<?php

namespace DUsmonov\LaravelModelGenerator\Processor;

use Illuminate\Database\DatabaseManager;
use DUsmonov\LaravelModelGenerator\Config;
use DUsmonov\LaravelModelGenerator\Exception\GeneratorException;
use DUsmonov\LaravelModelGenerator\Model\EloquentModel;

/**
 * Class ExistenceCheckerProcessor
 * @package DUsmonov\LaravelModelGenerator\Processor
 */
class ExistenceCheckerProcessor implements ProcessorInterface
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * ExistenceCheckerProcessor constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @inheritdoc
     */
    public function process(EloquentModel $model, Config $config)
    {
        $schemaManager = $this->databaseManager->connection($config->get('connection'))->getDoctrineSchemaManager();
        $prefix = $this->databaseManager->connection($config->get('connection'))->getTablePrefix();

        if (!$schemaManager->tablesExist($prefix . $model->getTableName())) {
            throw new GeneratorException(sprintf('Table %s does not exist', $prefix . $model->getTableName()));
        }
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 8;
    }
}

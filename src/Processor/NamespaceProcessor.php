<?php

namespace DUsmonov\LaravelModelGenerator\Processor;

use Krlove\CodeGenerator\Model\NamespaceModel;
use DUsmonov\LaravelModelGenerator\Config;
use DUsmonov\LaravelModelGenerator\Model\EloquentModel;

/**
 * Class NamespaceProcessor
 * @package DUsmonov\LaravelModelGenerator\Processor
 */
class NamespaceProcessor implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function process(EloquentModel $model, Config $config)
    {
        $model->setNamespace(new NamespaceModel($config->get('namespace')));
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 6;
    }
}

<?php

namespace DUsmonov\LaravelModelGenerator\Processor;

use Krlove\CodeGenerator\Model\NamespaceModel;
use DUsmonov\LaravelModelGenerator\Config\Config;
use DUsmonov\LaravelModelGenerator\Model\EloquentModel;

class NamespaceProcessor implements ProcessorInterface
{
    public function process(EloquentModel $model, Config $config): void
    {
        $model->setNamespace(new NamespaceModel($config->getNamespace()));
    }

    public function getPriority(): int
    {
        return 6;
    }
}

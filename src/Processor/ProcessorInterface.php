<?php

namespace DUsmonov\LaravelModelGenerator\Processor;

use DUsmonov\LaravelModelGenerator\Config;
use DUsmonov\LaravelModelGenerator\Model\EloquentModel;

/**
 * Interface ProcessorInterface
 * @package DUsmonov\LaravelModelGenerator\Processor
 */
interface ProcessorInterface
{
    /**
     * @param EloquentModel $model
     * @param Config $config
     */
    public function process(EloquentModel $model, Config $config);

    /**
     * @return int
     */
    public function getPriority();
}

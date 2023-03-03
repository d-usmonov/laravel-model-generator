<?php

namespace DUsmonov\LaravelModelGenerator\Processor;

use DUsmonov\LaravelModelGenerator\Config\Config;
use DUsmonov\LaravelModelGenerator\Model\EloquentModel;

interface ProcessorInterface
{
    public function process(EloquentModel $model, Config $config): void;
    public function getPriority(): int;
}

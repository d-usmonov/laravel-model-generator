<?php

namespace DUsmonov\LaravelModelGenerator\EventListener;

use Illuminate\Console\Events\CommandStarting;
use DUsmonov\LaravelModelGenerator\TypeRegistry;

class GenerateCommandEventListener
{
    private const SUPPORTED_COMMANDS = [
        'modelyarat:generate:model',
        'modelyarat:generate:models',
    ];

    public function __construct(private TypeRegistry $typeRegistry) {}

    public function handle(CommandStarting $event): void
    {
        if (!in_array($event->command, self::SUPPORTED_COMMANDS)) {
            return;
        }

        $userTypes = config('laravel_model_generator.db_types', []);
        foreach ($userTypes as $type => $value) {
            $this->typeRegistry->registerType($type, $value);
        }
    }
}
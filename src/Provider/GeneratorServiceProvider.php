<?php

namespace DUsmonov\LaravelModelGenerator\Provider;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use DUsmonov\LaravelModelGenerator\Command\GenerateModelCommand;
use DUsmonov\LaravelModelGenerator\Command\GenerateModelsCommand;
use DUsmonov\LaravelModelGenerator\EventListener\GenerateCommandEventListener;
use DUsmonov\LaravelModelGenerator\Generator;
use DUsmonov\LaravelModelGenerator\Processor\CustomPrimaryKeyProcessor;
use DUsmonov\LaravelModelGenerator\Processor\CustomPropertyProcessor;
use DUsmonov\LaravelModelGenerator\Processor\FieldProcessor;
use DUsmonov\LaravelModelGenerator\Processor\NamespaceProcessor;
use DUsmonov\LaravelModelGenerator\Processor\RelationProcessor;
use DUsmonov\LaravelModelGenerator\Processor\TableNameProcessor;
use DUsmonov\LaravelModelGenerator\TypeRegistry;

class GeneratorServiceProvider extends ServiceProvider
{
    public const PROCESSOR_TAG = 'laravel_model_generator.processor';

    public function register()
    {
        $this->commands([
            GenerateModelCommand::class,
            GenerateModelsCommand::class,
        ]);

        $this->app->singleton(TypeRegistry::class);
        $this->app->singleton(GenerateCommandEventListener::class);

        $this->app->tag([
            FieldProcessor::class,
            NamespaceProcessor::class,
            RelationProcessor::class,
            CustomPropertyProcessor::class,
            TableNameProcessor::class,
            CustomPrimaryKeyProcessor::class,
        ], self::PROCESSOR_TAG);

        $this->app->bind(Generator::class, function ($app) {
            return new Generator($app->tagged(self::PROCESSOR_TAG));
        });
    }

    public function boot()
    {
        Event::listen(CommandStarting::class, [GenerateCommandEventListener::class, 'handle']);
    }
}

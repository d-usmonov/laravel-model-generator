<?php

namespace DUsmonov\LaravelModelGenerator\Provider;

use Illuminate\Support\ServiceProvider;
use DUsmonov\LaravelModelGenerator\Command\GenerateModelCommand;
use DUsmonov\LaravelModelGenerator\EloquentModelBuilder;
use DUsmonov\LaravelModelGenerator\Processor\CustomPrimaryKeyProcessor;
use DUsmonov\LaravelModelGenerator\Processor\CustomPropertyProcessor;
use DUsmonov\LaravelModelGenerator\Processor\ExistenceCheckerProcessor;
use DUsmonov\LaravelModelGenerator\Processor\FieldProcessor;
use DUsmonov\LaravelModelGenerator\Processor\NamespaceProcessor;
use DUsmonov\LaravelModelGenerator\Processor\RelationProcessor;
use DUsmonov\LaravelModelGenerator\Processor\TableNameProcessor;

/**
 * Class GeneratorServiceProvider
 * @package DUsmonov\LaravelModelGenerator\Provider
 */
class GeneratorServiceProvider extends ServiceProvider
{
    const PROCESSOR_TAG = 'eloquent_model_generator.processor';

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->commands([
            GenerateModelCommand::class,
        ]);

        $this->app->tag([
            ExistenceCheckerProcessor::class,
            FieldProcessor::class,
            NamespaceProcessor::class,
            RelationProcessor::class,
            CustomPropertyProcessor::class,
            TableNameProcessor::class,
            CustomPrimaryKeyProcessor::class,
        ], self::PROCESSOR_TAG);

        $this->app->bind(EloquentModelBuilder::class, function ($app) {
            return new EloquentModelBuilder($app->tagged(self::PROCESSOR_TAG));
        });
    }
}
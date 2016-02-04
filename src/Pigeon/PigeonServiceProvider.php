<?php

namespace Larablocks\Pigeon;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class PigeonServiceProvider
 * @package Pigeon
 *
 */
class PigeonServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Required for testing
        //require __DIR__ . '../../../bootstrap/autoload.php';

        // Bind the library desired to the interface
        $this->app->bind('Larablocks\Pigeon\PigeonInterface', 'Larablocks\Pigeon\\'.config('pigeon.library'));

        // Bind the Pigeon Interface to the facade
        $this->app->bind('pigeon', 'Larablocks\Pigeon\PigeonInterface');

        // Load Pigeon alias for the user if not set in app.php
        $aliases = config('app.aliases');
        if (empty($aliases['Pigeon'])) {
            AliasLoader::getInstance()->alias('Pigeon', 'Larablocks\Pigeon\Pigeon');
        }
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pigeon.php' => config_path('pigeon.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../views/' => base_path('resources/views'),
        ], 'views');

    }

}
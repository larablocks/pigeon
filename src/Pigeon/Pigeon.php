<?php namespace Larablocks\Pigeon;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

class Pigeon extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return App::make('pigeon');
    }
}
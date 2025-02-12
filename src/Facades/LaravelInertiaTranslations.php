<?php

namespace CodeCNG\LaravelInertiaTranslations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodeCNG\LaravelInertiaTranslations\LaravelInertiaTranslations
 */
class LaravelInertiaTranslations extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodeCNG\LaravelInertiaTranslations\LaravelInertiaTranslations::class;
    }
}

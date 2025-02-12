<?php

namespace CodeCNG\LaravelInertiaTranslations;

use CodeCNG\LaravelInertiaTranslations\Commands\LaravelInertiaTranslationsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelInertiaTranslationsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-inertia-translations')
            ->hasCommand(LaravelInertiaTranslationsCommand::class);
    }
}

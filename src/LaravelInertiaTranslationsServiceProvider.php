<?php

namespace CodeCNG\LaravelInertiaTranslations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use CodeCNG\LaravelInertiaTranslations\Commands\LaravelInertiaTranslationsCommand;

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

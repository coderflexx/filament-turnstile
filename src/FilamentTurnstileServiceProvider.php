<?php

namespace Coderflex\FilamentTurnstile;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTurnstileServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-turnstile')
            ->hasViews('turnstile');
    }
}

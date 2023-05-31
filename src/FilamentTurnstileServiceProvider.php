<?php

namespace Coderflex\FilamentTurnstile;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentTurnstileServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-turnstile')
            ->hasViews('turnstile');
    }
}

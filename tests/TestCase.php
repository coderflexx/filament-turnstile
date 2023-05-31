<?php

namespace Coderflex\FilamentTurnstile\Tests;

use Coderflex\FilamentTurnstile\FilamentTurnstileServiceProvider;
use Coderflex\FilamentTurnstile\Tests\Fixtures\Login;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $this->registerLivewireComponents();
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentTurnstileServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        //
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('login', Login::class);
    }
}

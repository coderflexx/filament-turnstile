<?php

namespace Coderflex\FilamentTurnstile\Tests;

use Coderflex\FilamentTurnstile\FilamentTurnstileServiceProvider;
use Coderflex\FilamentTurnstile\Tests\Fixtures\TurnstilePanelProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Coderflex\\FilamentTurnstile\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $this->setCurrentFilamentPanel();
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentTurnstileServiceProvider::class,
            TurnstilePanelProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migrations = [
            include __DIR__.'/Database/Migrations/create_users_table.php',
        ];

        collect($migrations)->each(
            fn ($migration) => $migration->up()
        );
    }

    protected function setCurrentFilamentPanel(): void
    {
        Filament::setCurrentPanel(
            Filament::getPanel('turnstile')
        );
    }
}

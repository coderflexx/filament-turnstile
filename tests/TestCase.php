<?php

namespace Coderflex\FilamentTurnstile\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Coderflex\FilamentTurnstile\FilamentTurnstileServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    // protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Coderflex\\FilamentTurnstile\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $this->setCurrentFilamentPanel();

        // Ensure DataStore is fresh for each test
        // The issue was that Livewire's DataStore singleton (which holds component state) was not being correctly reset between tests in this environment, causing state persistence failures.
        $this->app->forgetInstance(\Livewire\Mechanisms\DataStore::class);
        $this->app->singleton(\Livewire\Mechanisms\DataStore::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            \Filament\Schemas\SchemasServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentTurnstileServiceProvider::class,
            TurnstilePanelProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__.'/resources/views',
        ]);

        $migrations = [
            include __DIR__.'/Database/Migrations/create_users_table.php',
            include __DIR__.'/Database/Migrations/create_contacts_table.php',
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

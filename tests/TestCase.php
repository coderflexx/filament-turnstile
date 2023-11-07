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
        config()->set('turnstile.turnstile_site_key', '2x00000000000000000000AB');
        config()->set('turnstile.turnstile_secret_key', '2x0000000000000000000000000000000AA');

        $this->setCurrentFilamentPanel();
    }

    protected function getPackageProviders($app)
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
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

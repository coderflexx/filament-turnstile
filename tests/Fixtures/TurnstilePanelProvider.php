<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;

class TurnstilePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('turnstile')
            ->path('filament')
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

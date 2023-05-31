<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;

class Login extends \Filament\Http\Livewire\Auth\Login
{
    protected function getFormSchema(): array
    {
        return array_merge(
            parent::getFormSchema(),
            [
                Turnstile::make('cf-captcha')
                    ->theme('auto'),
            ]
        );
    }
}

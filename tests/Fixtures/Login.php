<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;

class Login extends \Filament\Pages\Auth\Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        Turnstile::make('cf-captcha')
                            ->theme('auto'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}

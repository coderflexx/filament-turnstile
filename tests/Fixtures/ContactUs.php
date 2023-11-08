<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Coderflex\FilamentTurnstile\Tests\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;

class ContactUs extends FormsComponent
{
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required(),
                        Forms\Components\TextInput::make('content')
                            ->label('Content')
                            ->required(),
                        Turnstile::make('cf-captcha')
                            ->theme('auto'),
                    ])
            )
                ->statePath('data')
                ->model(Contact::class),
        ];
    }

    public function send()
    {
        Contact::create($this->form->getState());
    }

    public function render()
    {
        return 'fixtures.contact-us';
    }
}

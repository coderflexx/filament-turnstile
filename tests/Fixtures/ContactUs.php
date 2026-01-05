<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Coderflex\FilamentTurnstile\Tests\Models\Contact;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ContactUs extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->required(),
                TextInput::make('content')
                    ->label('Content')
                    ->required(),
                Turnstile::make('cf-captcha')
                    ->theme('auto'),
            ])
            ->statePath('data')
            ->model(Contact::class);
    }

    public function send()
    {
        Contact::create($this->form->getState());
    }

    public function render()
    {
        return 'fixtures.contact-us';
    }

    protected function onValidationError(ValidationException $exception): void
    {
        $this->dispatch('reset-captcha');
    }
}

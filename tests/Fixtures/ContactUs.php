<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Coderflex\FilamentTurnstile\Tests\Models\Contact;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ContactUs extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $cfCaptcha = null;

    protected function rules()
    {
        return [
            'data.name' => 'required',
            'data.cfCaptcha' => 'required|string',
        ];
    }

    public function getErrorBag()
    {
        return new MessageBag;
    }

    protected $validationAttributes = [
        'data.name' => 'name',
        'data.email' => 'email',
        'data.content' => 'content',
        'data.cf-captcha' => 'captcha',
    ];

    public function form(Schema $form): Schema
    {
        return $form
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
                Turnstile::make('cfCaptcha')
                    ->theme('auto'),
            ])
            ->statePath('data')
            ->model(Contact::class);
    }

    public function send()
    {
        $this->validate();

        $data = $this->form->getState();

        Contact::create($data);
    }

    public function render()
    {
        return view('fixtures.contact-us');
    }

    protected function onValidationError(ValidationException $exception): void
    {
        $this->dispatch('reset-captcha');
    }
}

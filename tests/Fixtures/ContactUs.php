<?php

namespace Coderflex\FilamentTurnstile\Tests\Fixtures;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Coderflex\FilamentTurnstile\Tests\Models\Contact;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\View\View;

class ContactUs extends SimplePage
{
    use InteractsWithFormActions;

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
                        Turnstile::make('turnstile')
                            // ->id('cf-captcha-field')
                            ->theme('auto'),
                    ])
            )
                ->statePath('data')
                ->model(Contact::class),
        ];
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSendFormAction(),
        ];
    }

    protected function getSendFormAction(): Action
    {
        return Action::make('Send')
            ->label(__('Send'))
            ->submit('send');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function send()
    {
        dd($this->form->getState());

        Contact::create($this->form->getState());
    }

    public function render(): View
    {
        return view('fixtures.contact-us');
    }
}

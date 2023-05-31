<?php

namespace Coderflex\FilamentTurnstile\Forms\Components;

use Coderflex\LaravelTurnstile\Rules\TurnstileCheck;
use Filament\Forms\Components\Field;

class Turnstile extends Field
{
    protected string $view = 'turnstile::components.turnstile';

    protected string $theme = 'auto';

    protected string $size = 'normal';

    protected string $language = 'en-US';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('');

        $this->rules(['required', new TurnstileCheck()]);

        $this->dehydrated(false);
    }

    public function theme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function size(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function language(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getTheme(): string
    {
        return $this->evaluate($this->theme);
    }

    public function getSize(): string
    {
        return $this->evaluate($this->size);
    }

    public function getLanguage(): string
    {
        return $this->evaluate($this->language);
    }
}

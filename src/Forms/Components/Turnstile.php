<?php

namespace Coderflex\FilamentTurnstile\Forms\Components;

use Coderflex\LaravelTurnstile\Rules\TurnstileCheck;
use Filament\Forms\Components\Field;

class Turnstile extends Field
{
    protected string $viewIdentifier = 'turnstile';

    protected string $view = 'turnstile::components.turnstile';

    protected string $theme = 'auto';

    protected string $size = 'normal';

    protected string $language = 'en-US';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('');

        $this->required();

        $this->rule(new TurnstileCheck());

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

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->evaluate($this->theme);
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->evaluate($this->size);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->evaluate($this->language);
    }
}

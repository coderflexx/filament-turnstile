<?php

use Coderflex\FilamentTurnstile\Tests\Fixtures\Login;
use function Pest\Livewire\livewire;

it('has captcha field', function () {
    livewire(Login::class)
        ->assertFormFieldExists('cf-captcha');
});

<?php

use Coderflex\FilamentTurnstile\Tests\Fixtures\Login;
use Coderflex\FilamentTurnstile\Tests\Fixtures\Register;

use function Pest\Livewire\livewire;

it('can render login page', function () {
    livewire(Login::class)
        ->assertOk();
});

it('can render register page', function () {
    livewire(Register::class)
        ->assertOk();
});

test('login page has captcha field', function () {
    livewire(Login::class)
        ->assertFormFieldExists('cf-captcha');
});

test('register page has captcha field', function () {
    livewire(Register::class)
        ->assertFormFieldExists('cf-captcha');
});

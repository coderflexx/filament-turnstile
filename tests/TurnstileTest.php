<?php

use Coderflex\FilamentTurnstile\Tests\Fixtures\ContactUs;

use function Pest\Livewire\livewire;

it('can render contact page', function () {
    livewire(ContactUs::class)
        ->assertOk();
});

test('contact page has captcha field', function () {
    livewire(ContactUs::class)
        ->assertFormFieldExists('cf-captcha', 'form');
});

<?php

use Coderflex\FilamentTurnstile\Tests\Fixtures\ContactUs;
use Coderflex\FilamentTurnstile\Tests\Models\Contact;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

use function Pest\Livewire\livewire;

it('can render contact page', function () {
    livewire(ContactUs::class)
        ->assertSuccessful();
});

test('contact page has captcha field', function () {
    livewire(ContactUs::class)
        ->assertFormFieldExists('cf-captcha', 'form');
});

it('can return success response', function () {
    /**
     * Setting Turnstile keys to always pass the request
     *
     * @link https://developers.cloudflare.com/turnstile/reference/testing/#dummy-sitekeys-and-secret-keys
     */
    Config::set('turnstile', [
        'turnstile_site_key' => '1x00000000000000000000AA',
        'turnstile_secret_key' => '1x0000000000000000000000000000000AA',
    ]);

    $response = LaravelTurnstile::validate('XXXX.DUMMY.TOKEN.XXXX');

    expect($response['success'])->toBeTrue();
});

it('can does not return success response', function () {
    /**
     * Setting Turnstile keys to always block the request
     *
     * @link https://developers.cloudflare.com/turnstile/reference/testing/#dummy-sitekeys-and-secret-keys
     */
    Config::set('turnstile', [
        'turnstile_site_key' => '2x00000000000000000000AB',
        'turnstile_secret_key' => '2x0000000000000000000000000000000AA',
    ]);

    $response = LaravelTurnstile::validate('XXXX.DUMMY.TOKEN.XXXX');

    expect($response['success'])->toBeFalse();
});

it('can send a message', function () {
    /**
     * In this context, Alpine.js didn't function as expected due to the need to pass the `cf-captcha` field.
     * The value of the mentioned key is dynamically determined by the response from Cloudflare (CF) in the UI.
     * For more information, refer to the Cloudflare Turnstile documentation:
     *
     * @link https://developers.cloudflare.com/turnstile/
     */
    Config::set('turnstile', [
        'turnstile_site_key' => '1x00000000000000000000AA',
        'turnstile_secret_key' => '1x0000000000000000000000000000000AA',
    ]);

    livewire(ContactUs::class)
        ->fillForm([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'content' => 'This is a simple message',
            'cf-captcha' => 'XXXX.DUMMY.TOKEN.XXXX',
        ])
        ->call('send')
        ->assertHasNoFormErrors();

    expect(Contact::get())
        ->toHaveCount(1);
});

it('cannot send a message', function () {
    Config::set('turnstile', [
        'turnstile_site_key' => '2x00000000000000000000AB',
        'turnstile_secret_key' => '2x0000000000000000000000000000000AA',
    ]);

    livewire(ContactUs::class)
        ->fillForm([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'content' => 'This is a simple message',
        ])
        ->call('send')
        ->assertHasFormErrors(['cf-captcha']);

    expect(Contact::get())
        ->toHaveCount(0);
});

it('reset captcha event sent, on validation error ', function () {
    Event::fake();

    Config::set('turnstile', [
        'turnstile_site_key' => '2x00000000000000000000AB',
        'turnstile_secret_key' => '2x0000000000000000000000000000000AA',
    ]);

    livewire(ContactUs::class)
        ->fillForm([
            'name' => null,
            'email' => 'john@example.com',
            'content' => 'This is a simple message',
        ])
        ->call('send')
        ->assertHasFormErrors(['name'])
        ->assertDispatched('reset-captcha');
});

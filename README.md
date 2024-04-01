# Filament Turnstile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coderflex/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/coderflex/filament-turnstile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/coderflexx/filament-turnstile/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/coderflexx/filament-turnstile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/coderflexx/filament-turnstile/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/coderflexx/filament-turnstile/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/coderflex/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/coderflex/filament-turnstile)

</br>

<img src="https://github.com/coderflexx/filament-turnstile/raw/main/art/thumbnail.png" alt="Login Screen screenshot" class="filament-hidden"/>

</br>

**Filament Turnstile** is an essential plugin designed to seamlessly integrate Cloudflare's turnstile into your applications.

This plugin uses [Laravel Turnstile](https://github.com/coderflexx/laravel-turnstile) under the hood. For detailed information, explore the [Laravel Turnstile README](https://github.com/coderflexx/laravel-turnstile).

## Installation
Install the package via Composer:

```bash
composer require coderflex/filament-turnstile
```

For users still on **Filament V2**, install the package using:

```bash
composer require coderflex/filament-turnstil "^1.0"
```

## Turnstile Keys
To utilize **Cloudflare Turnstile**, obtain your `SiteKey` and `SecretKey` from your Cloudflare Dashboard.

Refer to the [documentation](https://developers.cloudflare.com/turnstile/get-started/#get-a-sitekey-and-secret-key) for detailed instructions.

After generating the **keys**, include them in your `.env` file using the following format:

```env
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

For testing purposes, you can use [Dummy site keys and secret keys](https://developers.cloudflare.com/turnstile/reference/testing/) provided by Cloudflare.

## Usage

Utilizing this plugin is incredibly straightforward. In your form, incorporate the following code:

```php
use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;

Turnstile::make('captcha')
    ->theme('auto') // accepts light, dark, auto
    ->language('en-US') // see below
    ->size('normal'), // accepts normal, compact
```

For a list of supported languages, refer to the [supported languages section](https://developers.cloudflare.com/turnstile/reference/supported-languages/). 

The `Turnstile` field offers various options; you can learn more about them in [the Cloudflare configuration section](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations).

## Turnstile Events

The Turnstile package provides events that you can leverage to manage the behavior of the captcha in various scenarios.

**Reset Event**

The `reset-captcha` event allows you to programmatically reset the captcha challenge. This can be useful when you want to:

- **Clear the challenge after a successful form submission:** This ensures a fresh captcha for the next user.
- **Reset the challenge upon validation errors:** Prevents users from being stuck with a previously solved captcha after encountering errors during form submission.

**Dispatching the Reset Event:**

There are two primary ways to dispatch the `reset-captcha` event:

**1. Using `onValidationError` Method:**

Filament provides the `onValidationError` method within your form's Livewire component. This method is automatically triggered whenever form [validation fails](https://filamentphp.com/docs/3.x/forms/validation#sending-validation-notifications). Here's how to utilize it:

```php
protected function onValidationError(ValidationException $exception): void
{
    $this->dispatch('reset-captcha');

    // Perform additional actions as necessary (e.g., display error messages)
}
```

In this example, the `reset-captcha` event is dispatched upon validation errors, ensuring the captcha is reset for the user's next attempt.

**2. Manual Dispatching:**

For scenarios where resetting the captcha is not directly tied to validation, you can manually dispatch the event using Filament's event dispatcher:

```php
$this->dispatch('reset-captcha');
```

**Using Reset Event in Login Page:**

To automatically reset the captcha on a failed login attempt in your login form's Livewire component, leverage the `throwFailureValidationException` method:

```php
protected function authenticate(): void
{
    // Perform authentication logic
    // ...

    if (! Auth::attempt($this->data)) {
        $this->throwFailureValidationException(
            [
                'email' => 'Invalid email or password.',
            ]
        );
    }

    // Redirect to success page or perform other actions
}
```

By throwing a validation exception with appropriate error messages, you trigger the `onValidationError` method, which in turn dispatches the `reset-captcha` event, effectively resetting the captcha for the next login attempt.

## Real-Life Example:

To implement the **Turnstile** captcha with the `Login` page in Filament, follow these steps:

Create a new `App/Filament/Pages/Auth/Login.php` class:

```php

namespace App\Filament\Pages\Auth;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as AuthLogin;

class Login extends AuthLogin
{
    /**
     * @return array<int|string, string|Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        Turnstile::make('captcha')
                            ->label('Captcha')
                            ->theme('auto'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    // if you want to reset the captcha in case of validation error
    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');

        parent::throwFailureValidationException();
    }
}
```

Then, override the `login()` method in your `PanelProvider` (e.g., `AdminPanelProvider`):

```php
namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class); // override the login page class.
            ...
    }
}
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Oussama](https://github.com/ousid)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [the License File](LICENSE.md) for more information.

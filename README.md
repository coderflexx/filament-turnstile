# Filament Turnstile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coderflex/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/coderflex/filament-turnstile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/coderflexx/filament-turnstile/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/coderflexx/filament-turnstile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/coderflexx/filament-turnstile/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/coderflexx/filament-turnstile/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/coderflex/filament-turnstile.svg?style=flat-square)](https://packagist.org/packages/coderflex/filament-turnstile)

</br>

![Login Screen screenshot](https://github.com/coderflexx/filament-turnstile/raw/1.x/art/login_screen.png)

</br>

__Filament Turnstile__, is a plugin to help you implement the Cloudflare turnstile. 

This plugin uses [Laravel Turnstile](https://github.com/coderflexx/laravel-turnstile) Behind the scene, you can head to the page __README__ to learn more.

## Installation
You can install the package via composer:


```bash
composer require coderflex/filament-turnstile
```


## Turnstile Keys
To be able to use __Cloudflare Turnstile__, you need to get the `SiteKey`, and the `SecretKey` from your [Cloudflare dashboard](https://developers.cloudflare.com/turnstile/get-started/#get-a-sitekey-and-secret-key)

After Generating the __keys__, use `TURNSTILE_SITE_KEY`, and `TURNSTILE_SECRET_KEY` in your `.env` file

```.env
TURNSTILE_SITE_KEY=2x00000000000000000000AB
TURNSTILE_SECRET_KEY=2x0000000000000000000000000000000AA
```

If you want to test the widget, you can use the [Dummy site keys and secret keys](https://developers.cloudflare.com/turnstile/reference/testing/) that Cloudflare provides.

## Usage

The usage of this plugin, is really straight - forward. In your form, use the following code:

```php
...
use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;

...
    Turnstile::make('captcha')
        ->theme('auto')
        ->language('fr')
        ->size('normal'),
```

The `Turnstile` field, has few options to use. You can learn more about them in [the Cloudflare configuration section](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations)

## Real Life Example:
In order to use __Turnstile__ captcha with the `Login` page in filament, use the following steps:

Create a new `App/Filament/Pages/Login.php` class

```php
<?php

namespace App\Filament\Pages;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;

class Login extends \Filament\Http\Livewire\Auth\Login
{
    protected function getFormSchema(): array
    {
        return array_merge(
            parent::getFormSchema(),
            [
                Turnstile::make('cf-captcha')
                    ->theme('auto')
                    ->language('en-US')
                    ->size('normal'),
            ]
        );
    }
}
```

Then override the `Login` class in the `filament.php` config file.

```php
    return [
        ....
        'auth' => [
            'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
            'pages' => [
                'login' => \App\Filament\Pages\Login::class,
            ],
        ],
        ...
    ]
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

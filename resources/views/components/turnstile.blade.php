@php
    $statePath = $getStatePath();
    $fieldWrapperView = $getFieldWrapperView();

    $theme = $getTheme();
    $size = $getSize();
    $language = $getLanguage();
@endphp

<x-dynamic-component :component="$fieldWrapperView" :field="$turnstile">

    <div x-data="{
            state: $wire.entangle('{{ $statePath }}').defer
        }"
         wire:ignore
         x-load-js="['https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback']"
         x-init="(() => {
            let options= {
                callback: function (token) {
                    $wire.set('{{ $statePath }}', token)
                },

                errorCallback: function () {
                    $wire.set('{{ $statePath }}', null)
                },
            }

            window.onloadTurnstileCallback = () => {
                turnstile.render($refs.turnstile, options)
            }

            resetCaptcha = () => {
                turnstile.reset($refs.turnstile)
            }

            $wire.on('reset-captcha', () => resetCaptcha())

            const observer = new IntersectionObserver((entries) => {
                  entries.forEach(entry => {
                      if (entry.isIntersecting && 
                          window.turnstile && 
                          !$refs.turnstile.querySelector('.cf-turnstile')) {
                          turnstile.render($refs.turnstile, options);
                      }
                  });
              }, { threshold: 0.1 })

            if ($refs.turnstile) {
                observer.observe($refs.turnstile);
            }
        })()"
    >
        <div data-sitekey="{{config('turnstile.turnstile_site_key')}}"
             data-theme="{{ $theme }}"
             data-language="{{ $language }}"
             data-size="{{ $size }}"
             x-ref="turnstile"
        >
        </div>
    </div>
</x-dynamic-component>

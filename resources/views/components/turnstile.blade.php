@php
    $statePath = $getStatePath();
    $fieldWrapperView = $getFieldWrapperView();

    $theme = $getTheme();
    $size = $getSize();
    $language = $getLanguage();
@endphp

<x-dynamic-component :component="$fieldWrapperView" :field="$turnstile">

    <div wire:ignore
         x-load-js="['https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=onTurnstileLoad']"
         x-data="{
            state: $wire.entangle('{{ $statePath }}').defer,
            widgetId: null,
        }"
         x-init="(() => {
            let options = {
                sitekey: '{{config('turnstile.turnstile_site_key')}}',
                theme: '{{ $theme }}',
                size: '{{ $size }}',
                language: '{{ $language }}',
                callback: function (token) {
                    $wire.set('{{ $statePath }}', token)
                },
                'error-callback': function () {
                    $wire.set('{{ $statePath }}', null)
                }
            }

            // Render widget when Turnstile API is ready
            const renderWidget = () => {
                if (!window.turnstile || !$refs.turnstile || widgetId !== null) {
                    return;
                }

                widgetId = turnstile.render($refs.turnstile, options);
            }

            // Called when Turnstile API loads
            window.onTurnstileLoad = () => {
                renderWidget();
            }

            // If API already loaded (on re-render), render immediately
            if (window.turnstile) {
                renderWidget();
            }

            $wire.on('reset-captcha', () => {
                if (widgetId !== null && window.turnstile) {
                    turnstile.reset(widgetId);
                }
            })

            // Cleanup when component is destroyed
            return () => {
                if (widgetId !== null && window.turnstile) {
                    turnstile.remove(widgetId);
                    widgetId = null;
                }
            }
        })()"
    >
        <div x-ref="turnstile"></div>
    </div>
</x-dynamic-component>

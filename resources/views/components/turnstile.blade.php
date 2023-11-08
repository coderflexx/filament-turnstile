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

    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback" defer></script>
    @endpush
</x-dynamic-component>
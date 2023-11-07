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
    >
        <div id="turnstile-widget"
            data-sitekey="{{config('turnstile.turnstile_site_key')}}"
            :data-theme="$theme"
            :data-language="$language"
            :data-size="$size"
            >
        </div>
    </div>

    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback" defer></script>
        <script>
            let options = {
                callback: function(token) {
                    window.Livewire
                        .find('{{$this->id}}')
                        .$set('{{$statePath}}', token);
                },
                errorCallback: function () {
                    window.Livewire
                        .find('{{$this->id}}')
                        .$set('{{$statePath}}', 'error');
                }
            }

            window.onloadTurnstileCallback = () => {
                turnstile.render('#turnstile-widget', options)
            }
        </script>
    @endpush
</x-dynamic-component>
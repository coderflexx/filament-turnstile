<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{ 
            state: $wire.entangle('{{ $getStatePath() }}').defer 
        }"
        wire:ignore
    >
        <div id="turnstile-widget"
            data-sitekey="{{config('turnstile.turnstile_site_key')}}"
            data-theme="{{$getTheme()}}"
            data-language="{{$getLanguage()}}"
            data-size="{{$getSize()}}">
        </div>
    </div>

    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback" defer></script>
        <script>
            let options = {
                callback: function(token) {
                    window.Livewire
                        .find('{{$this->id}}')
                        .$set('{{$getStatePath()}}', token);
                },
                errorCallback: function () {
                    window.Livewire
                        .find('{{$this->id}}')
                        .$set('{{$getStatePath()}}', 'error');
                }
            }

            window.onloadTurnstileCallback = () => {
                turnstile.render('#turnstile-widget', options)
            }
        </script>
    @endpush
</x-dynamic-component>
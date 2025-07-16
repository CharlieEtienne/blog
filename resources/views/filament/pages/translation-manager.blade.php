@php
    use Filament\Actions\Action;
    use Filament\Support\Facades\FilamentView;
@endphp
<x-filament-panels::page>
    <form class="fi-sc-form" wire:submit="create">
        {{ $this->form }}

        <div class="fi-sc fi-sc-has-gap fi-grid" style="--cols-default: repeat(1, minmax(0, 1fr));">
            <div
                x-data="filamentSchemaComponent({
                    path: '',
                    containerPath: '',
                    isLive: false,
                    $wire,
                })"
                class="fi-grid-col" style="--col-span-default: span 1 / span 1;"
            >
                <div class="fi-sc-component">
                    <div class="fi-sc-actions">
                        <div class="fi-ac fi-align-start">

                            {{
                                Action::make('save')
                                    ->label(__('Save translations'))
                                    ->submit('save')
                                    ->action(null)
                                    ->keyBindings(['mod+s'])
                            }}

                            {{
                                Action::make('back')
                                    ->label(__('Cancel'))
                                    ->alpineClickHandler(
                                        FilamentView::hasSpaMode(filament()->getUrl())
                                            ? 'document.referrer ? window.history.back() : Livewire.navigate(' . Js::from(filament()->getUrl()) . ')'
                                            : 'document.referrer ? window.history.back() : (window.location.href = ' . Js::from(filament()->getUrl()) . ')',
                                    )
                                    ->color('gray')
                            }}

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

<x-filament-actions::modals />
</x-filament-panels::page>

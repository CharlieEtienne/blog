@php use Filament\Actions\Action; @endphp
<x-filament-panels::page>
    <form wire:submit="create">
        {{ $this->form }}

        {{
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save')
                ->action(null)
                ->keyBindings(['mod+s'])
        }}

    </form>

<x-filament-actions::modals />
</x-filament-panels::page>

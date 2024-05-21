<div>
    <!-- HEADER -->
    <x-header title="People" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live.debounce="search" icon="o-magnifying-glass" placeholder="Search..." clearable
                title="Search for name or surname." />
        </x-slot:middle>

        <x-slot:actions>
            <x-button @click="$wire.drawer = true" icon="o-funnel" label="Filters" responsive />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$people" :sort-by="$sortBy" with-pagination>
            @scope('cell_birth_date', $person)
                {{ $person->birth_date->format('Y-m-d') }}
            @endscope

            @scope('cell_interests', $person)
                @foreach ($person->interests as $interest)
                    <x-badge :value="$interest->name" class="badge-primary badge-sm" />
                @endforeach
            @endscope

            @scope('actions', $person)
                <x-button wire:click="delete({{ $person->id }})" icon="o-trash" wire:confirm="Are you sure?" spinner
                    class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass"
            @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>

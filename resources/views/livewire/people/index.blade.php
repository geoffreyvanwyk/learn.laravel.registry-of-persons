<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

use App\Models\Language;
use App\Models\Person;

new class () extends Component {
    use Toast;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    /**
     * Reset the filters.
     */
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    /**
     * Deregister a person.
     */
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }

    /**
     * Headers of the columns of the register table.
     */
    public function headers(): array
    {
        return [
            ['key' => 'id',                 'label' => '#',                 'class' => 'w-1'],
            ['key' => 'name',               'label' => 'Name',              'class' => 'w-32'],
            ['key' => 'surname',            'label' => 'Surname',           'class' => 'w-32'],
            ['key' => 'south_african_id',   'label' => 'South African ID',  'class' => 'w-16'],
            ['key' => 'mobile_number',      'label' => 'Mobile Number',     'class' => 'w-32'],
            ['key' => 'email',              'label' => 'Email Address',     'class' => 'w-32'],
            ['key' => 'birth_date',         'label' => 'Birth Date',        'class' => 'w-16'],
            ['key' => 'language',           'label' => 'Language',          'class' => 'w-16'],
            ['key' => 'interests',          'label' => 'Interests',         'class' => 'w-32'],
        ];
    }

    /**
     * The people in the registry.
     */
    public function people(): Collection
    {
        return Person::all()
            ->map(function ($person) {
                $p = $person->toArray();
                $p['language'] = __('languages.' . Language::find($person['language_id'])->code);
                $p['interests'] = $person->interests->pluck('name')->join(', ');
                return $p;
            })
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(function (array $item) {
                    return str($item['name'])->contains($this->search, true)
                        || str($item['surname'])->contains($this->search, true);
                });
            });
    }

    public function with(): array
    {
        return [
            'people' => $this->people(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="People" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$people" :sort-by="$sortBy">
            @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>

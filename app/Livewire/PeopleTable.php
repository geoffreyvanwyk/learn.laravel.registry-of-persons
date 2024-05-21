<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

use App\Models\Person;

class PeopleTable extends Component
{
    use Toast;
    use WithPagination;

    /**
     * Filter the people in the table by searching through names and surnames.
     */
    public string $search = '';

    /**
     * Sort the people in the table.
     */
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    /**
     * Should the filter drawer on the side be open or closed?
     */
    public bool $drawer = false;

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('livewire.people.index')->with([
            'headers' => $this->headers(),
            'people'  => $this->people(),
        ]);
    }

    /**
     * Headers of the columns of the table.
     *
     * @return  array<array<string,string>>
     */
    public function headers(): array
    {
        return [
            ['key' => 'id',                 'label' => '#',                 'class' => 'w-1'],
            ['key' => 'name',               'label' => 'Name',              'class' => 'w-16'],
            ['key' => 'surname',            'label' => 'Surname',           'class' => 'w-16'],
            ['key' => 'south_african_id',   'label' => 'South African ID',  'class' => 'w-16'],
            ['key' => 'mobile_number',      'label' => 'Mobile Number',     'class' => 'w-16'],
            ['key' => 'email',              'label' => 'Email Address',     'class' => 'w-16'],
            ['key' => 'birth_date',         'label' => 'Birth Date',        'class' => 'w-32'],
            ['key' => 'language.name',           'label' => 'Language',          'class' => 'w-16',  'sortBy' => 'language_name'],
            ['key' => 'interests',          'label' => 'Interests',         'class' => 'w-64',  'sortable' => false],
        ];
    }

    /**
     * The people in the registry.
     */
    public function people(): LengthAwarePaginator
    {
        return Person::query()
            ->with(['language', 'interests'])
            ->withAggregate('language', 'name')
            ->when($this->search, function (Builder $query) {
                return $query->whereAny(['name', 'surname'], 'LIKE', "%$this->search%");
            })
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }

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
    public function delete(Person $person): void
    {
        $this->warning("Will delete #$person->id", 'It is fake.', position: 'toast-bottom');
    }
}

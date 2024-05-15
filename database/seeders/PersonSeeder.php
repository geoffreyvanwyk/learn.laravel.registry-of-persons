<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $people = Person::factory()->count(10)->create();

        foreach ($people as $person) {
            $person->interests()->attach(Interest::all()->random(rand(1, 3))->pluck('id'));
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Person;
use App\Notifications\PersonRegistered;

class PersonObserver
{
    /**
     * Handle the Person "created" event.
     */
    public function created(Person $person): void
    {
        $person->notify(new PersonRegistered());
    }
}

<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Person $person): bool
    {
        return $user->canManagePerson($person);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Person $person): bool
    {
        return $user->canManagePerson($person);
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->canManagePerson($person);
    }
}

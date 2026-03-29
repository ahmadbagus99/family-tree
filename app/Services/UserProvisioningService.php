<?php

namespace App\Services;

use App\Models\Marriage;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserProvisioningService
{
    /**
     * Buat/ perbarui akun login untuk laki-laki yang sudah beristri (punya rekaman pernikahan).
     */
    public function syncForMarriage(Marriage $marriage): void
    {
        foreach ([$marriage->person1_id, $marriage->person2_id] as $personId) {
            $person = Person::find($personId);
            if ($person && $person->gender === 'male') {
                $this->ensureUserForMale($person);
            }
        }
    }

    public function ensureUserForMale(Person $person): ?User
    {
        if ($person->gender !== 'male') {
            return null;
        }

        $hasMarriage = Marriage::query()
            ->where('person1_id', $person->id)
            ->orWhere('person2_id', $person->id)
            ->exists();

        if (! $hasMarriage) {
            return null;
        }

        $email = $person->id.'@family-tree.local';

        $user = User::firstOrNew(['person_id' => $person->id]);
        $user->name = $person->name;
        $user->username = $this->makeUniqueUsername($person);
        $user->email = $email;
        $user->is_super_admin = false;
        if (! $user->exists) {
            $user->password = Hash::make('admin');
        }
        $user->save();

        return $user;
    }

    /**
     * Sinkronkan semua laki-laki beristri di database (setelah seed / deploy).
     */
    public function syncAllMarriedMales(): int
    {
        $count = 0;
        $maleIds = Marriage::query()
            ->get()
            ->flatMap(fn (Marriage $m) => [$m->person1_id, $m->person2_id])
            ->unique();

        foreach ($maleIds as $pid) {
            $person = Person::find($pid);
            if ($person && $person->gender === 'male') {
                $this->ensureUserForMale($person);
                $count++;
            }
        }

        return $count;
    }

    public function makeUsernameForPerson(Person $person): string
    {
        return $this->makeUniqueUsername($person);
    }

    /**
     * Sinkronkan nama/username di users jika data Person berubah (tanpa reset password).
     */
    public function syncLinkedUserFromPerson(Person $person): void
    {
        $user = User::query()->where('person_id', $person->id)->first();
        if (! $user) {
            return;
        }
        $user->name = $person->name;
        $user->username = $this->makeUniqueUsername($person);
        $user->email = $person->id.'@family-tree.local';
        $user->save();
    }

    protected function makeUniqueUsername(Person $person): string
    {
        $exact = trim($person->name);
        if ($exact !== '') {
            $q = User::query()->where('username', $exact);
            $q->where(function ($q2) use ($person) {
                $q2->whereNull('person_id')->orWhere('person_id', '!=', $person->id);
            });
            if (! $q->exists()) {
                return $exact;
            }
        }

        $slug = Str::slug($person->name ?: 'user', '');
        if ($slug === '') {
            $slug = 'user';
        }

        return $slug.'-'.$person->id;
    }
}

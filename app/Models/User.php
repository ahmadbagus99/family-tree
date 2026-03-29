<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'email', 'password', 'person_id', 'username', 'is_super_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * @return Collection<int, int>
     */
    public function manageablePersonIds(): Collection
    {
        if ($this->is_super_admin) {
            return Person::query()->pluck('id');
        }

        if ($this->person_id === null) {
            return collect();
        }

        $person = $this->person;
        if (! $person) {
            return collect();
        }

        return Person::manageableBranchIdsFor($person);
    }

    public function canManagePerson(Person $person): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->manageablePersonIds()->contains($person->id);
    }

    public function canManagePersonId(int $personId): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->manageablePersonIds()->contains($personId);
    }
}

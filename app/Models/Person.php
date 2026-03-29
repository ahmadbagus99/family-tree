<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Person extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'death_date',
        'gender',
        'parent_id',
        'generation',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    /**
     * URL publik foto (memakai disk public + APP_URL). Gunakan ini di view, bukan asset('storage/...').
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->photo) {
                return null;
            }

            return Storage::disk('public')->url($this->photo);
        });
    }

    // Parent relationship
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'parent_id');
    }

    // Children relationship
    public function children(): HasMany
    {
        return $this->hasMany(Person::class, 'parent_id');
    }

    // Marriages relationship
    public function marriages(): HasMany
    {
        return $this->hasMany(Marriage::class, 'person1_id')
            ->orWhere('person2_id', $this->id);
    }

    // Spouse relationship
    public function spouse(): HasOne
    {
        return $this->hasOne(Marriage::class, 'person1_id')
            ->select('id', 'person1_id', 'person2_id', 'marriage_date')
            ->with(['person2']);
    }

    public function person1Marriages()
    {
        return $this->hasMany(Marriage::class, 'person1_id');
    }

    public function person2Marriages()
    {
        return $this->hasMany(Marriage::class, 'person2_id');
    }

    public function getSpouses()
    {
        $spouse1 = $this->person1Marriages->map(fn ($m) => $m->person2);
        $spouse2 = $this->person2Marriages->map(fn ($m) => $m->person1);

        return $spouse1->concat($spouse2);
    }
}

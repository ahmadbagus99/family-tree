<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marriage extends Model
{
    protected $fillable = [
        'person1_id',
        'person2_id',
        'marriage_date',
    ];

    protected $casts = [
        'marriage_date' => 'date',
    ];

    public function person1(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person1_id');
    }

    public function person2(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person2_id');
    }
}

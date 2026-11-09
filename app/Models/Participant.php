<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Participant extends Model
{
    protected $fillable = ['name', 'email'];

    // A participant joins many events (through registrations)
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'registrations')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
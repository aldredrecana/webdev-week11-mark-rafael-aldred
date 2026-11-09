<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    protected $fillable = ['venue_id', 'title', 'event_date'];

    // An event belongs to one venue
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    // An event has many participants (through registrations)
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class, 'registrations')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // An event can have many notes
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
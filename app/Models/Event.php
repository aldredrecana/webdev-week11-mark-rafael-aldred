<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
protected $fillable = ['venue_id', 'title', 'event_date', 'description', 'details', 'starts_at'];

protected $casts = [
    'details'   => 'array',
    'starts_at' => 'datetime',
];

  
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

    // Scope with no parameter: events that have not started yet
public function scopeUpcoming(Builder $query): Builder
{
    return $query->where('starts_at', '>=', now());
}

public function scopeAtVenue(Builder $query, int $venueId): Builder
{
    return $query->where('venue_id', $venueId);
}

public function scopeWithDressCode(Builder $query, string $dressCode): Builder
{
    return $query->where('details->dress_code', $dressCode);
}

public function scopeSearch(Builder $query, string $text): Builder
{
    return $query->whereFullText('description', $text);
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Venue;

class ReportController extends Controller
{
    public function index()
    {
        // Query 1: every venue with the number of events it hosts
        $venuesWithCount = Venue::withCount('events')->get();

        // Query 2: all events at one chosen venue
        $chosenVenue = Venue::with('events')->findOrFail(1);

        // Query 3: all participants of one chosen event, with status
        $chosenEvent = Event::with('participants')->findOrFail(1);

        // Query 4: all events one chosen participant has joined
        $chosenParticipant = Participant::with('events')->findOrFail(1);

        // Query 5: events with at least one waitlisted participant
        $waitlistedEvents = Event::whereHas('participants', function ($q) {
            $q->where('registrations.status', 'waitlisted');
        })->get();

        // Query 6: all notes of one event and all notes of one venue
        $eventNotes = Event::findOrFail(1)->notes;
        $venueNotes = Venue::findOrFail(1)->notes;

        // Query 7: one note, what it belongs to, and what kind of thing
        $note = Note::with('notable')->findOrFail(1);
        $noteKind = class_basename($note->notable_type);
        $noteOwnerName = $note->notable instanceof Event
            ? $note->notable->title
            : $note->notable->name;

        return view('report', compact(
            'venuesWithCount',
            'chosenVenue',
            'chosenEvent',
            'chosenParticipant',
            'waitlistedEvents',
            'eventNotes',
            'venueNotes',
            'note',
            'noteKind',
            'noteOwnerName'
        ));
    }
}
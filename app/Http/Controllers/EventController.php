<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->query('mode', 'fixed');   // /events?mode=naive or /events?mode=fixed

        DB::enableQueryLog();

        if ($mode === 'naive') {
            // Lazy loading: 1 query for events, then 1 per event for venue, 1 per event for participants
            $events = Event::orderBy('id')->limit(50)->get();
            $rows = $events->map(fn ($e) => [
                'title' => $e->title,
                'venue' => $e->venue->name,
                'participants' => $e->participants->count(),
            ]);
        } else {
            // Eager loading: venue loaded together, participant count done inside the query
            $events = Event::with('venue')->withCount('participants')->orderBy('id')->limit(50)->get();
            $rows = $events->map(fn ($e) => [
                'title' => $e->title,
                'venue' => $e->venue->name,
                'participants' => $e->participants_count,
            ]);
        }

        $queryCount = count(DB::getQueryLog());

        return view('events.index', compact('rows', 'mode', 'queryCount'));
    }
}
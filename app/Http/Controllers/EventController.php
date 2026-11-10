<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EventController extends Controller
{
    public function index(Request $request)
{
    $mode = $request->query('mode', 'fixed');   // /events?mode=naive or /events?mode=fixed

    DB::enableQueryLog();

    if ($mode === 'naive') {
        // Plain naive page: allow lazy loading so we can count the queries (101).
        // Add &strict=1 to the URL to keep preventLazyLoading ON and show the error.
        if (! $request->boolean('strict')) {
            Model::preventLazyLoading(false);
        }

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

    public function filter(Request $request)
{
    $dress = $request->query('dress', 'barong');

    // Way 1: arrow form
    $arrowQuery = Event::with('venue')
        ->where('details->dress_code', $dress)
        ->orderBy('id')
        ->limit(50);

    // Way 2: containment
    $containQuery = Event::with('venue')
        ->whereJsonContains('details', ['dress_code' => $dress])
        ->orderBy('id')
        ->limit(50);

    return view('events.filter', [
        'dress'         => $dress,
        'arrowSql'      => $arrowQuery->toSql(),
        'arrowBindings' => $arrowQuery->getBindings(),
        'arrowEvents'   => $arrowQuery->get(),
        'containSql'      => $containQuery->toSql(),
        'containBindings' => $containQuery->getBindings(),
        'containEvents'   => $containQuery->get(),
    ]);
}

public function search(Request $request)
{
    $q = trim($request->query('q', ''));
    $venue = $request->query('venue');   // optional, lets us chain scopes

    $events = collect();
    $sql = null;

    if ($q !== '') {
        $query = Event::search($q)                                     // scope 1: full-text
            ->when($venue, fn ($qb) => $qb->atVenue((int) $venue))     // scope 2: chained
            ->with('venue')
            ->orderBy('id')
            ->limit(50);

        $sql = $query->toSql();
        $events = $query->get();
    }

    return view('events.search', compact('q', 'events', 'sql'));
}
}
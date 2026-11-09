<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Events Report</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 30px auto; padding: 0 15px; }
        h1 { color: #1f4e79; }
        h2 { background: #1f4e79; color: white; padding: 8px 12px; font-size: 16px; margin-top: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Campus Events Report</h1>

    <h2>Query 1: Every venue with the number of events it hosts</h2>
    <table>
        <tr><th>Venue</th><th>Number of events</th></tr>
        @foreach ($venuesWithCount as $venue)
            <tr><td>{{ $venue->name }}</td><td>{{ $venue->events_count }}</td></tr>
        @endforeach
    </table>

    <h2>Query 2: All events at one chosen venue ({{ $chosenVenue->name }})</h2>
    <table>
        <tr><th>Event</th><th>Date</th></tr>
        @foreach ($chosenVenue->events as $event)
            <tr><td>{{ $event->title }}</td><td>{{ $event->event_date }}</td></tr>
        @endforeach
    </table>

    <h2>Query 3: All participants of one chosen event ({{ $chosenEvent->title }}), with status</h2>
    <table>
        <tr><th>Participant</th><th>Status</th></tr>
        @foreach ($chosenEvent->participants as $participant)
            <tr><td>{{ $participant->name }}</td><td>{{ $participant->pivot->status }}</td></tr>
        @endforeach
    </table>

    <h2>Query 4: All events one chosen participant has joined ({{ $chosenParticipant->name }})</h2>
    <table>
        <tr><th>Event</th><th>Status</th></tr>
        @foreach ($chosenParticipant->events as $event)
            <tr><td>{{ $event->title }}</td><td>{{ $event->pivot->status }}</td></tr>
        @endforeach
    </table>

    <h2>Query 5: Events that have at least one waitlisted participant</h2>
    <table>
        <tr><th>Event</th><th>Date</th></tr>
        @foreach ($waitlistedEvents as $event)
            <tr><td>{{ $event->title }}</td><td>{{ $event->event_date }}</td></tr>
        @endforeach
    </table>

    <h2>Query 6: All notes of one event and all notes of one venue</h2>
    <p><strong>Notes of the event:</strong></p>
    <ul>
        @foreach ($eventNotes as $n)
            <li>{{ $n->body }}</li>
        @endforeach
    </ul>
    <p><strong>Notes of the venue:</strong></p>
    <ul>
        @foreach ($venueNotes as $n)
            <li>{{ $n->body }}</li>
        @endforeach
    </ul>

    <h2>Query 7: For one note, the name of the thing it belongs to and what kind it is</h2>
    <table>
        <tr><th>Note</th><th>Belongs to</th><th>Kind</th></tr>
        <tr>
            <td>{{ $note->body }}</td>
            <td>{{ $noteOwnerName }}</td>
            <td>{{ $noteKind }}</td>
        </tr>
    </table>
</body>
</html>
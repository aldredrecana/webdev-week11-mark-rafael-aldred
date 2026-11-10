<!DOCTYPE html>
<html>

<head>
    <title>Search events</title>
</head>

<body>
    <h1>Search events</h1>

    <form method="GET" action="/events/search">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search description">
        <button type="submit">Search</button>
    </form>

    @if ($sql)
        <p>SQL: <code>{{ $sql }}</code></p>
        <p>Results shown: {{ $events->count() }} (maximum 50)</p>
        <ul>
            @foreach ($events as $event)
                <li>{{ $event->title }} | {{ $event->venue->name }} | {{ $event->description }}</li>
            @endforeach
        </ul>
    @endif
</body>

</html>

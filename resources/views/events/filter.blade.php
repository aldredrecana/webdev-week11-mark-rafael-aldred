<!DOCTYPE html>
<html>

<head>
    <title>Filter events by JSONB</title>
</head>

<body>
    <h1>Events with dress_code = "{{ $dress }}"</h1>

    <h2>Way 1: arrow form <code>where('details->dress_code', ...)</code></h2>
    <p>SQL: <code>{{ $arrowSql }}</code></p>
    <p>Bindings: <code>{{ json_encode($arrowBindings) }}</code></p>
    <p>Results shown: {{ $arrowEvents->count() }}</p>
    <ul>
        @foreach ($arrowEvents as $event)
            <li>{{ $event->title }} | {{ $event->venue->name }} | {{ $event->details['dress_code'] }}</li>
        @endforeach
    </ul>

    <h2>Way 2: containment <code>whereJsonContains('details', [...])</code></h2>
    <p>SQL: <code>{{ $containSql }}</code></p>
    <p>Bindings: <code>{{ json_encode($containBindings) }}</code></p>
    <p>Results shown: {{ $containEvents->count() }}</p>
    <ul>
        @foreach ($containEvents as $event)
            <li>{{ $event->title }} | {{ $event->venue->name }} | {{ $event->details['dress_code'] }}</li>
        @endforeach
    </ul>
</body>

</html>

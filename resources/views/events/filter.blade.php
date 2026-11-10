<!DOCTYPE html>
<html>

<head>
    <title>Filter events by JSONB</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #f4f6fb;
            color: #1f2937;
            margin: 0;
            padding: 32px;
        }

        h1 {
            margin: 0 0 8px;
            color: #7a0019;
        }

        /* PUP maroon */
        .badge {
            display: inline-block;
            background: #7a0019;
            color: #fff;
            padding: 4px 12px;
            border-radius: 999px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        table {
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        }

        th {
            background: #7a0019;
            color: #fff;
            text-align: left;
        }

        th,
        td {
            padding: 10px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) td {
            background: #faf7f8;
        }

        code,
        .sql {
            display: block;
            background: #1f2937;
            color: #a7f3d0;
            padding: 12px;
            border-radius: 8px;
            font-family: Consolas, monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 8px 0 16px;
        }

        ul {
            background: #fff;
            padding: 16px 16px 16px 36px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        }

        input[type=text] {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        button {
            padding: 8px 16px;
            background: #7a0019;
            color: #fff;
            border: 0;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
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

<!DOCTYPE html>
<html>

<head>
    <title>Search events</title>
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

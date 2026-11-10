<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
</head>
<body>
    <h1>Events ({{ $mode }} version)</h1>
    <p><strong>Queries sent: {{ $queryCount }}</strong></p>

    <table border="1" cellpadding="6">
        <tr><th>Title</th><th>Venue</th><th>Participants</th></tr>
        @foreach ($rows as $row)
            <tr>
                <td>{{ $row['title'] }}</td>
                <td>{{ $row['venue'] }}</td>
                <td>{{ $row['participants'] }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
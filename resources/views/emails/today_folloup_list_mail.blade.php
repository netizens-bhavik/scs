<html>
<head>
    <title>Today's Followup List</title>
</head>
<body>
    <h1>Today's Followup List</h1>
    <p>
        Here's the list of today's followup list
    </p>
    <table border="2px">
        <thead>
            <tr>
                <th>DATE-TIME</th>
                <th>USER NAME</th>
                <th>COMPANY NAME</th>
                <th>CONTACT PERSON</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['records'] as $followup)
                @php
                    $followup_datetime = date('d/m/Y', strtotime($followup->next_followup_date)) . ' ' . date('h:i:A',
                    strtotime($followup->next_followup_time));
                @endphp
                <tr>
                    <td>{{ $followup_datetime}}</td>
                    <td>{{ $followup->user_name}}</td>
                    <td>{{ $followup->company_name }}</td>
                    <td>{{ $followup->contact_person }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Notes Reminder</h1>
    <p>Hi {{ $details['user_name'] }},</p>
    <p>Here are your notes for today:</p>
    <h3>{{ $details['title'] }}</h3>
    <p>{{ $details['description'] }}</p>
    <span>Time : {{ $details['reminder_at'] }} </span>
</body>

</html>

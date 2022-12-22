<html>
<head>
    <title>Shared Mom with you</title>
</head>
<body>
<h1>Shared Mom with you</h1>
<p>
    Hi {{ $data['name'] }},
    {{ $data['bodyMessage'] }}
</p>

<span>
    <a href="{{ $data['url'] }}">Click here to view</a>
</span>

<span>
    <p>Regards</p>
    <p>Team {{ config('app.name') }}</p>
</span>
</body>
</html>

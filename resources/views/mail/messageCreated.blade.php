<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message Received</title>
</head>
<body>
<h1>Hello, {{ $receiver->name }}</h1>
<p>There is new message for you sent by {{ $sender->name }}!</p>
<p>{{ $body }}</p>
<p>Follow the link to see the details:</p>
<a href="{{ URL::signedRoute('api.chats.show', ['chat' => $chat->id]) }}">Show the details</a>
</body>
</html>
